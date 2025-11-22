<?php

namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Piso;
use App\Models\TipoEspacio;
use Livewire\Attributes\On;
use Livewire\Component;

class EspaciosList extends Component
{
    // Propiedades que se vinculan con el Blade view
    public $pisoId;
    public $filtroEstado = "todos";
    public $filtroTipo = "todos";
    public $busqueda = "";
    public $tipos;

    // Propiedades para crear nuevos espacios
    public $mostrarModal = false;
    public $nuevoEspacio = [
        'codigo' => '',
        'tipo_espacio_id' => '',
        'estado' => 'libre'
    ];

    // Propiedad para controlar si está en dashboard
    public $enDashboard = false;

    public function mount($enDashboard = false)
    {
        $this->enDashboard = $enDashboard;
        // Carga los tipos de espacio al iniciar el componente
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
    public function refrescarEspacios()
    {
        // Se refresca automáticamente al recibir eventos
    }

    // Métodos para manejar nuevos espacios
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
        ], [
            'nuevoEspacio.codigo.required' => 'El código del espacio es obligatorio',
            'nuevoEspacio.codigo.unique' => 'Este código ya existe',
            'nuevoEspacio.tipo_espacio_id.required' => 'El tipo de espacio es obligatorio',
        ]);

        try {
            Espacio::create([
                'codigo' => $this->nuevoEspacio['codigo'],
                'tipo_espacio_id' => $this->nuevoEspacio['tipo_espacio_id'],
                'estado' => $this->nuevoEspacio['estado'],
                'piso_id' => $this->pisoId,
            ]);

            $this->cerrarModal();
            $this->dispatch('espacioCreado');
            session()->flash('message', 'Espacio creado exitosamente.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el espacio: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if (!$this->pisoId) {
            $pisoPredeterminado = Piso::where("numero", 1)->first();
            $this->pisoId = $pisoPredeterminado?->id ?? null;

            if (!$this->pisoId) {
                $this->pisoId = Piso::first()?->id;
            }
        }

        $query = Espacio::where("piso_id", $this->pisoId)->with("tipoEspacio");

        if ($this->filtroEstado !== "todos") {
            $query->where("estado", $this->filtroEstado);
        }

        if ($this->filtroTipo !== "todos") {
            $query->where("tipo_espacio_id", $this->filtroTipo);
        }

        if (!empty($this->busqueda)) {
            $query->where(function ($q) {
                $q->where("codigo", "LIKE", "%{$this->busqueda}%")
                    ->orWhereHas("ticketActivo", function ($sub) {
                        $sub->where("placa", "LIKE", "%{$this->busqueda}%");
                    });
            });
        }

        return view("livewire.espacios-list", [
            "espacios" => $query->orderBy("codigo")->get(),
            "pisoId" => $this->pisoId,
        ]);
    }
}
