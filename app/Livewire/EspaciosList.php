<?php

namespace App\Livewire;

use App\Models\Espacio;
use App\Models\Piso;
use App\Models\TipoEspacio;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class EspaciosList extends Component
{
    // Propiedades que se vinculan con el Blade view
    public $pisoId;
    public $filtroEstado = "todos"; // todos, libre, ocupado
    public $filtroTipo = "todos";
    public $busqueda = "";
    public $tipos;

    // Nota: El método actualizarBusqueda() ya no es necesario gracias a wire:model.live

    public function mount()
    {
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
    public function refrescarEspacios()
    {
        // No necesitas lógica aquí, el simple hecho de llamar al método
        // provoca que el componente se "refresque" y traiga los datos nuevos de la BD.
    }
    public function render()
    {
        if (!$this->pisoId) {
            $pisoPredeterminado = Piso::where("numero", 1)->first();
            $this->pisoId = $pisoPredeterminado?->id ?? null;

            // Opcional: si no existe el piso 1, toma el primer piso disponible
            if (!$this->pisoId) {
                $this->pisoId = Piso::first()?->id;
            }
        }

        // 2. Definición de la consulta base
        $query = Espacio::where("piso_id", $this->pisoId)->with("tipoEspacio");

        // 3. Aplicar Filtro de estado
        if ($this->filtroEstado !== "todos") {
            $query->where("estado", $this->filtroEstado);
        }

        // 4. Aplicar Filtro tipo de espacio
        if ($this->filtroTipo !== "todos") {
            $query->where("tipo_espacio_id", $this->filtroTipo);
        }

        // 5. Aplicar Búsqueda por placa o código
        if (!empty($this->busqueda)) {
            $query->where(function ($q) {
                // Búsqueda por Código de Espacio
                $q->where("codigo", "LIKE", "%{$this->busqueda}%")
                    // Búsqueda por Placa del Ticket Activo
                    ->orWhereHas("ticketActivo", function ($sub) {
                        $sub->where("placa", "LIKE", "%{$this->busqueda}%");
                    });
            });
        }

        // 6. Retorno de la vista con los resultados
        return view("livewire.espacios-list", [
            "espacios" => $query->orderBy("codigo")->get(),
            "pisoId" => $this->pisoId,
        ]);
    }
}
