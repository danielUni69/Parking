<?php

namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Pago;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse; // Necesario para el tipo de retorno

class TicketFinalizar extends Component
{
    public $espacio;
    public $ticket;
    public $monto;
    public $horas;
    public $minutos;
    public $tarifaBase;
    public $tarifaNocturna;
    public $desgloseHoras;
    // Eliminamos $pagoId ya que no lo necesitamos como propiedad pública si se usa localmente.

    #[On("finalizarTicketDeEspacio")]
    public function abrirModal($espacioId)
    {
        $this->espacio = Espacio::find($espacioId);

        // Usamos la relación para obtener el ticket activo
        $this->ticket = $this->espacio->ticketActivo;

        if (!$this->ticket) {
            return;
        }

        // Asegúrate de que TipoEspacio está cargado si es necesario para tarifa_hora
        $this->tarifaBase = $this->espacio->tipoEspacio->tarifa_hora ?? 0;
        $this->tarifaNocturna = $this->tarifaBase + 2; // +2 Bs desde las 6pm

        // 1. Calculamos minutos reales
        $inicio = Carbon::parse($this->ticket->horaIngreso);
        $fin = now();

        $totalMinutos = $inicio->diffInMinutes($fin);

        // 2. LÓGICA DE COBRO: Redondear siempre hacia arriba
        // Calculamos las horas redondeadas
        $this->horas = ceil($totalMinutos / 60);

        // SEGURIDAD: Si entraron y salieron en el mismo minuto (0 min), forzamos a 1 hora
        if ($this->horas < 1) {
            $this->horas = 1;
        }

        // 3. Calcular Monto con tarifa variable según horario
        $this->monto = $this->calcularMontoConTarifaVariable(
            $inicio,
            $fin,
            $this->horas,
        );

        // Guardamos los minutos reales para mostrar en el modal
        $this->minutos = $totalMinutos;

        $this->dispatch("abrir-modal-finalizar");
    }

    private function calcularMontoConTarifaVariable(
        $inicio,
        $fin,
        $horasTotales,
    ) {
        $montoTotal = 0;
        $horaActual = $inicio->copy();

        // Definir horarios de tarifa nocturna (18:00 a 06:00)
        $inicioTarifaNocturna = 18; // 6pm
        $finTarifaNocturna = 6; // 6am

        $this->desgloseHoras = [
            "normales" => 0,
            "nocturnas" => 0,
        ];

        for ($hora = 0; $hora < $horasTotales; $hora++) {
            $horaDelDia = $horaActual->hour;

            // Determinar si es horario nocturno
            $esNocturno =
                $horaDelDia >= $inicioTarifaNocturna ||
                $horaDelDia < $finTarifaNocturna;

            // Aplicar tarifa correspondiente
            $tarifaHora = $esNocturno
                ? $this->tarifaNocturna
                : $this->tarifaBase;

            $montoTotal += $tarifaHora;

            // Contar horas para el desglose
            if ($esNocturno) {
                $this->desgloseHoras["nocturnas"]++;
            } else {
                $this->desgloseHoras["normales"]++;
            }

            $horaActual->addHour();
        }

        return round($montoTotal, 2);
    }

    /**
     * Finaliza el ticket, registra el pago, libera el espacio y descarga el PDF del recibo.
     * * @return mixed|\Illuminate\Http\Response|StreamedResponse Retorna la descarga del PDF o vacío si falla.
     */
    public function finalizar()
    {
        if (!$this->ticket) {
            return;
        }

        $pagoCreado = null; // Variable para almacenar el pago creado

        // 1. Ejecutar las operaciones de DB en una transacción
        DB::transaction(function () use (&$pagoCreado) {
            // A. Cerrar el Ticket
            $this->ticket->update([
                "horaSalida" => now(),
                "estado" => "finalizado",
                "costo_total" => $this->monto,
            ]);

            // B. Registrar el Pago
            $pagoCreado = Pago::create([
                "monto" => $this->monto,
                "fecha" => now(),
                "ticket_id" => $this->ticket->id,
                "metodo" => "efectivo", // O el método de pago seleccionado
            ]);

            // C. Liberar el Espacio
            $this->espacio->update(["estado" => "libre"]);

            // D. Notificar a la interfaz de que el ticket ha finalizado
            // NOTA: Livewire procesará estos despachos DESPUÉS de que la descarga se complete.
            $this->dispatch("ticketFinalizado");
            $this->dispatch("cerrar-modal-finalizar");
        });

        // 2. Generar y devolver el PDF (solo si el pago se creó con éxito)
        if (!$pagoCreado) {
            // Si la transacción falló, simplemente retornamos para evitar errores.
            return;
        }

        // Recargamos las relaciones necesarias en el objeto $pagoCreado
        // Se necesitan 'ticket', 'espacio' y 'tipoEspacio' para la vista del PDF.
        $pagoCreado->load([
            "ticket" => function ($query) {
                $query->with(["espacio.tipoEspacio"]);
            },
        ]);

        // Calcular datos para el PDF usando la información final del ticket/pago
        // Usamos la horaSalida recién guardada
        $minutosReales = Carbon::parse(
            $pagoCreado->ticket->horaIngreso,
        )->diffInMinutes($pagoCreado->ticket->horaSalida);
        $horasCobro = ceil($minutosReales / 60);

        // Preparamos los datos
        $data = [
            "pago" => $pagoCreado,
            "ticket" => $pagoCreado->ticket,
            "espacio" => $pagoCreado->ticket->espacio,
            "tipoEspacio" => $pagoCreado->ticket->espacio->tipoEspacio,
            "horasCobro" => $horasCobro,
            "minutosReales" => $minutosReales,
        ];

        // Cargar la vista Blade ('pdf.ticket-pdf') y generar el PDF
        $pdf = Pdf::loadView("pdf.ticket-pdf", $data)
            // Configuración para un ticket de 80mm de ancho (226.77pt)
            ->setPaper([0, 0, 320.77, 600], "portrait")
            ->setOptions([
                "isHtml5ParserEnabled" => true,
                "isRemoteEnabled" => true,
            ]);

        $nombreArchivo =
            "ticket-pago-" .
            $pagoCreado->id .
            "-" .
            now()->format("YmdHis") .
            ".pdf";

        // 3. Devolver la descarga del archivo como stream
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $nombreArchivo);
    }

    public function render()
    {
        return view("livewire.ticket-finalizar");
    }
}
