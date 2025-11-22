<?php

namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Pago;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;

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

    #[On("finalizarTicketDeEspacio")]
    public function abrirModal($espacioId)
    {
        $this->espacio = Espacio::find($espacioId);

        // Usamos la relación para obtener el ticket activo
        $this->ticket = $this->espacio->ticketActivo;

        if (!$this->ticket) {
            return;
        }

        $this->tarifaBase = $this->espacio->tipoEspacio->tarifa_hora;
        $this->tarifaNocturna = $this->tarifaBase + 2; // +2 Bs desde las 6pm

        // 1. Calculamos minutos reales
        $inicio = Carbon::parse($this->ticket->horaIngreso);
        $fin = now();

        $totalMinutos = $inicio->diffInMinutes($fin);

        // 2. LÓGICA DE COBRO: Redondear siempre hacia arriba
        $this->horas = ceil($totalMinutos / 60);

        // SEGURIDAD: Si entraron y salieron en el mismo minuto (0 min), forzamos a 1 hora
        if ($this->horas < 1) {
            $this->horas = 1;
        }

        // 3. Calcular Monto con tarifa variable según horario
        $this->monto = $this->calcularMontoConTarifaVariable($inicio, $fin, $this->horas);

        // Guardamos los minutos reales para mostrar en el modal
        $this->minutos = $totalMinutos;

        $this->dispatch("abrir-modal-finalizar");
    }

    private function calcularMontoConTarifaVariable($inicio, $fin, $horasTotales)
    {
        $montoTotal = 0;
        $horaActual = $inicio->copy();

        // Definir horarios de tarifa nocturna (18:00 a 06:00)
        $inicioTarifaNocturna = 18; // 6pm
        $finTarifaNocturna = 6;     // 6am

        $this->desgloseHoras = [
            'normales' => 0,
            'nocturnas' => 0
        ];

        for ($hora = 0; $hora < $horasTotales; $hora++) {
            $horaDelDia = $horaActual->hour;

            // Determinar si es horario nocturno
            $esNocturno = ($horaDelDia >= $inicioTarifaNocturna || $horaDelDia < $finTarifaNocturna);

            // Aplicar tarifa correspondiente
            $tarifaHora = $esNocturno ? $this->tarifaNocturna : $this->tarifaBase;

            $montoTotal += $tarifaHora;

            // Contar horas para el desglose
            if ($esNocturno) {
                $this->desgloseHoras['nocturnas']++;
            } else {
                $this->desgloseHoras['normales']++;
            }

            $horaActual->addHour();
        }

        return round($montoTotal, 2);
    }

    public function finalizar()
    {
        if (!$this->ticket) {
            return;
        }

        // 1. Cerrar el Ticket
        $this->ticket->update([
            "horaSalida" => now(),
            "estado" => "finalizado",
            "costo_total" => $this->monto,
        ]);

        // 2. Registrar el Pago
        Pago::create([
            "monto" => $this->monto,
            "fecha" => now(),
            "ticket_id" => $this->ticket->id,
            "metodo" => "efectivo",
        ]);

        // 3. Liberar el Espacio
        $this->espacio->update(["estado" => "libre"]);

        // 4. Notificar a la interfaz
        $this->dispatch("ticketFinalizado");
        $this->dispatch("cerrar-modal-finalizar");
    }

    public function render()
    {
        return view("livewire.ticket-finalizar");
    }
}
