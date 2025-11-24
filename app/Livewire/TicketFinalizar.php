<?php
namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Pago;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $this->tarifaBase = $this->espacio->tipoEspacio->tarifa_hora;
        $this->tarifaNocturna = $this->tarifaBase + 2;

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
            'normales' => 0,
            'nocturnas' => 0
        ];

        for ($hora = 0; $hora < $horasTotales; $hora++) {
            $horaDelDia = $horaActual->hour;
            $esNocturno = ($horaDelDia >= $inicioTarifaNocturna || $horaDelDia < $finTarifaNocturna);
            $tarifaHora = $esNocturno ? $this->tarifaNocturna : $this->tarifaBase;
            $montoTotal += $tarifaHora;

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

        $this->ticket->update([
            "horaSalida" => now(),
            "estado" => "finalizado",
            "costo_total" => $this->monto,
        ]);

        Pago::create([
            "monto" => $this->monto,
            "fecha" => now(),
            "ticket_id" => $this->ticket->id,
            "metodo" => "efectivo",
        ]);

        $this->espacio->update(["estado" => "libre"]);

        $pdf = Pdf::loadView('tickets.ticket-pdf', [
            'espacio' => $this->espacio,
            'ticket' => $this->ticket,
            'horas' => $this->horas,
            'minutos' => $this->minutos,
            'tarifaBase' => $this->tarifaBase,
            'tarifaNocturna' => $this->tarifaNocturna,
            'monto' => $this->monto,
            'desgloseHoras' => $this->desgloseHoras,
        ]);

        $this->dispatch("ticketFinalizado");
        $this->dispatch("cerrar-modal-finalizar");

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "ticket-{$this->ticket->id}.pdf"
        );
    }

    public function render()
    {
        return view("livewire.ticket-finalizar");
    }
}
