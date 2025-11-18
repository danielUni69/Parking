<?php

namespace App\Livewire;

use App\Models\Piso;
use Livewire\Component;

class PisosList extends Component
{
    public $pisos;
    public $pisoSeleccionado = null;

    public function mount()
    {
        $this->pisos = Piso::orderBy("numero")->get();
    }

    public function seleccionarPiso($pisoId)
    {
        $this->pisoSeleccionado = $pisoId;
        $this->dispatch("pisoSeleccionado", $pisoId);
    }
    public function render()
    {
        return view("livewire.pisos-list");
    }
}
