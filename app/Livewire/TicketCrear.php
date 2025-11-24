<?php

namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Ticket;
use App\Models\PlacaFormato;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class TicketCrear extends Component
{
    public $espacio;
    public $placa;
    public $formato_id;
    public $formatos;

    public function mount()
    {
        $this->formatos = PlacaFormato::orderBy('pais', 'asc')->get();
        $this->formato_id = PlacaFormato::where('code', 'BO')->value('id');
    }

    #[On("crearTicketParaEspacio")]
    public function abrirModal($espacioId)
    {
        $this->espacio = Espacio::find($espacioId);
        if (!$this->espacio) return;

        $this->placa = "";
        $this->formato_id = PlacaFormato::where('code', 'BO')->value('id');
        $this->dispatch("abrir-modal-crear");
    }

    public function crearTicket()
    {
        $this->validate([
            "formato_id" => "required|exists:placa_formatos,id",
            "placa" => "required|min:3|max:12",
        ]);

        $formato = PlacaFormato::find($this->formato_id);
        $placaMayus = strtoupper($this->placa);
        if (!preg_match("/{$formato->regex}/", $placaMayus)) {
            return $this->addError("placa", "La placa no coincide con el formato del país seleccionado ({$formato->pais}).");
        }
        $ticketExistente = Ticket::where("placa", $placaMayus)
            ->where("estado", "activo")
            ->first();

        if ($ticketExistente) {
            return $this->addError("placa", "Esta placa ya tiene un ingreso activo.");
        }
        Ticket::create([
            "placa" => $placaMayus,
            "espacio_id" => $this->espacio->id,
            "usuario_id" => Auth::id(),
            "horaIngreso" => now(),
            "estado" => "activo",
        ]);
        $this->espacio->update(["estado" => "ocupado"]);
        $this->dispatch("ticketCreado");
        $this->dispatch("cerrar-modal-crear");
    }

    public function render()
    {
        return view("livewire.ticket-crear");
    }
}
