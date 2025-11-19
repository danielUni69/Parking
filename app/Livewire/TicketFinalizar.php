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

    #[On("finalizarTicketDeEspacio")]
    public function abrirModal($espacioId)
    {
        $this->espacio = Espacio::find($espacioId);

        // Usamos la relación para obtener el ticket activo
        $this->ticket = $this->espacio->ticketActivo;

        if (!$this->ticket) {
            return;
        }

        $tarifa = $this->espacio->tipoEspacio->tarifa_hora;

        // 1. Calculamos minutos reales
        $inicio = Carbon::parse($this->ticket->horaIngreso);
        $fin = now();

        $totalMinutos = $inicio->diffInMinutes($fin);

        // 2. LÓGICA DE COBRO: Redondear siempre hacia arriba
        // ceil(0.1) -> 1
        // ceil(1.1) -> 2
        $this->horas = ceil($totalMinutos / 60);

        // SEGURIDAD: Si entraron y salieron en el mismo minuto (0 min), forzamos a 1 hora
        // para cumplir la regla de "siempre cobrar por una hora".
        if ($this->horas < 1) {
            $this->horas = 1;
        }

        // 3. Calcular Monto
        $this->monto = round($this->horas * $tarifa, 2);

        // Guardamos los minutos reales para mostrar en el modal
        $this->minutos = $totalMinutos;

        $this->dispatch("abrir-modal-finalizar");
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
