<?php

namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Piso;
use App\Models\TipoEspacio;
use Livewire\Attributes\On;
use Livewire\Component;

class EspaciosList extends Component
{
    public $pisoId;
    public $filtroEstado = "todos";
    public $filtroTipo = "todos";
    public $busqueda = "";
    public $tipos;

    public $mostrarModal = false;
    public $nuevoEspacio = [
        'codigo' => '',
        'tipo_espacio_id' => '',
        'estado' => 'libre'
    ];

    public $enDashboard = false;

    public function mount($enDashboard = false)
    {
        $this->enDashboard = $enDashboard;
        $this->tipos = TipoEspacio::orderBy("nombre")->get();
    }

    #[On("pisoSeleccionado")]
    public function cargarEspacios($pisoId)
    {
        $this->pisoId = $pisoId;
    }

    #[On("ticketCreado")]
    #[On("ticketFinalizado")]
    #[On("espacioCreado")]
    public function refrescarEspacios() {}

    public function abrirModalCrear()
    {
        $this->reset('nuevoEspacio');
        $this->nuevoEspacio['estado'] = 'libre';
        $this->mostrarModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->resetErrorBag();
    }

    public function crearEspacio()
    {
        $this->validate([
            'nuevoEspacio.codigo' => 'required|string|max:50|unique:espacio,codigo',
            'nuevoEspacio.tipo_espacio_id' => 'required|exists:tipo_espacios,id',
            'nuevoEspacio.estado' => 'required|in:libre,ocupado',
        ]);

        Espacio::create([
            'codigo' => $this->nuevoEspacio['codigo'],
            'tipo_espacio_id' => $this->nuevoEspacio['tipo_espacio_id'],
            'estado' => $this->nuevoEspacio['estado'],
            'piso_id' => $this->pisoId,
        ]);

        $this->cerrarModal();
        $this->dispatch('espacioCreado');
        session()->flash('message', 'Espacio creado exitosamente.');
    }

    public function render()
    {
        if (!$this->pisoId) {
            $this->pisoId = Piso::where("numero", 1)->first()?->id
                ?? Piso::first()?->id;
        }

        $query = Espacio::where("piso_id", $this->pisoId);

        if ($this->filtroEstado !== "todos") {
            $query->where("estado", $this->filtroEstado);
        }

        if ($this->filtroTipo !== "todos") {
            $query->where("tipo_espacio_id", $this->filtroTipo);
        }

        if (!empty($this->busqueda)) {
            $query->where(function ($q) {
                $q->where("codigo", "LIKE", "%{$this->busqueda}%")
                    ->orWhereHas("ticketActivo", fn($sub) =>
                        $sub->where("placa", "LIKE", "%{$this->busqueda}%")
                    );
            });
        }

        return view("livewire.espacios-list", [
            "espacios" => $query->with("ticketActivo")->orderBy("codigo")->get(),
            "pisoId" => $this->pisoId,
        ]);
    }
}
