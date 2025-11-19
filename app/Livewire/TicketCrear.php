<?php

namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class TicketCrear extends Component
{
    public $espacio;
    public $placa;
    public $mostrarModal = false; // Controlamos el estado desde PHP para mayor seguridad si se desea

    #[On("crearTicketParaEspacio")]
    public function abrirModal($espacioId)
    {
        $this->espacio = Espacio::find($espacioId);

        if (!$this->espacio) {
            return;
        }

        $this->placa = "";

        // Disparamos el evento de navegador para AlpineJS
        $this->dispatch("abrir-modal-crear");
    }

    public function crearTicket()
    {
        $this->validate([
            "placa" => "required|min:3|max:10", // Validación mejorada
        ]);

        Ticket::create([
            "placa" => strtoupper($this->placa), // Guardamos la placa en mayúsculas
            "espacio_id" => $this->espacio->id,
            "usuario_id" => Auth::id(),
            "horaIngreso" => now(),
            "estado" => "activo",
        ]);

        // Actualizamos el estado del espacio a ocupado (id 4 asumiendo lógica, o string 'ocupado')
        $this->espacio->update(["estado" => "ocupado"]);

        $this->dispatch("ticketCreado"); // Para recargar la lista de espacios
        $this->dispatch("cerrar-modal-crear"); // Para cerrar el modal
    }

    public function render()
    {
        return view("livewire.ticket-crear");
    }
}
