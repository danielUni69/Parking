<?php
namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Pago;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $this->ticket = $this->espacio->ticketActivo;

        if (!$this->ticket) {
            return;
        }

        $this->tarifaBase = $this->espacio->tipoEspacio->tarifa_hora ?? 0;
        $this->tarifaNocturna = $this->tarifaBase + 2; // +2 Bs desde 18:00

        $inicio = Carbon::parse($this->ticket->horaIngreso)->setTimezone('America/La_Paz');
        $fin = now()->setTimezone('America/La_Paz');
        $totalMinutos = $inicio->diffInMinutes($fin);
        $this->horas = ceil($totalMinutos / 60);
        if ($this->horas < 1) {
            $this->horas = 1;
        }

        $this->monto = $this->calcularMontoConTarifaVariable($inicio, $fin, $this->horas);
        $this->minutos = $totalMinutos;
        $this->dispatch("abrir-modal-finalizar");
    }

    private function calcularMontoConTarifaVariable($inicio, $fin, $horasTotales)
    {
        $montoTotal = 0;
        $horaActual = $inicio->copy();
        $inicioTarifaNocturna = 18;
        $finTarifaNocturna = 6;
        $this->desgloseHoras = [
            "normales" => 0,
            "nocturnas" => 0,
        ];

        for ($i = 0; $i < $horasTotales; $i++) {
            $horaDelDia = $horaActual->hour;
            $esNocturno = ($horaDelDia >= $inicioTarifaNocturna || $horaDelDia < $finTarifaNocturna);
            $tarifaHora = $esNocturno ? $this->tarifaNocturna : $this->tarifaBase;
            $montoTotal += $tarifaHora;
            if ($esNocturno) {
                $this->desgloseHoras["nocturnas"]++;
            } else {
                $this->desgloseHoras["normales"]++;
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

        $pagoCreado = null;
        DB::transaction(function () use (&$pagoCreado) {
            $this->ticket->update([
                "horaSalida" => now(),
                "estado" => "finalizado",
                "costo_total" => $this->monto,
            ]);

            $pagoCreado = Pago::create([
                "monto" => $this->monto,
                "fecha" => now(),
                "ticket_id" => $this->ticket->id,
                "metodo" => "efectivo",
            ]);

            $this->espacio->update(["estado" => "libre"]);
            $this->dispatch("ticketFinalizado");
            $this->dispatch("cerrar-modal-finalizar");
        });

        if (!$pagoCreado) {
            return;
        }

        $pagoCreado->load([
            "ticket" => function ($query) {
                $query->with(["espacio.tipoEspacio"]);
            },
        ]);

        $minutosReales = Carbon::parse($pagoCreado->ticket->horaIngreso)
            ->diffInMinutes($pagoCreado->ticket->horaSalida);
        $horasCobro = ceil($minutosReales / 60);

        $data = [
            "espacio" => $pagoCreado->ticket->espacio,
            "ticket" => $pagoCreado->ticket,
            "tarifaBase" => $this->tarifaBase,
            "tarifaNocturna" => $this->tarifaNocturna,
            "desgloseHoras" => $this->desgloseHoras,
            "monto" => $this->monto,
            "horas" => $horasCobro,
            "minutos" => $minutosReales,
        ];

        $pdf = Pdf::loadView("tickets.ticket-pdf", $data)
            ->setPaper([0, 0, 320.77, 600], "portrait")
            ->setOptions([
                "isHtml5ParserEnabled" => true,
                "isRemoteEnabled" => true,
            ]);

        $nombreArchivo = "ticket-pago-" . $pagoCreado->id . "-" . now()->format("YmdHis") . ".pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $nombreArchivo);
    }

    public function render()
    {
        return view("livewire.ticket-finalizar");
    }
}
