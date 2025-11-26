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
    public $modoEliminar = false; // Nombre variable mantenido, pero lógica es Deshabilitar

    // Solo pedimos el tipo, el resto es automático
    public $nuevoEspacio = [
        "tipo_espacio_id" => "",
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

    // --- Manejo de Modos ---

    public function toggleModoEliminar()
    {
        $this->modoEliminar = !$this->modoEliminar;
    }

    public function gestionarClickEspacio($espacioId)
    {
        if ($this->modoEliminar) {
            // Confirmar Deshabilitación
            $this->confirmarEliminacion($espacioId);
        } else {
            // Lógica normal de tickets
            $espacio = Espacio::find($espacioId);
            if ($espacio) {
                if ($espacio->estado === "libre") {
                    $this->dispatch(
                        "crearTicketParaEspacio",
                        espacioId: $espacioId,
                    );
                } else {
                    $this->dispatch(
                        "finalizarTicketDeEspacio",
                        espacioId: $espacioId,
                    );
                }
            }
        }
    }

    // --- Lógica SweetAlert ---

    public function confirmarEliminacion($id)
    {
        $espacio = Espacio::find($id);

        if ($espacio->estado === "ocupado" || $espacio->ticketActivo) {
            $this->dispatch("swal:error", [
                "title" => "¡Imposible!",
                "text" =>
                    "No puedes deshabilitar un espacio que tiene un vehículo dentro.",
            ]);
            return;
        }

        $this->dispatch("swal:confirm", [
            "title" => "¿Deshabilitar Espacio?",
            "text" => "El espacio {$espacio->codigo} pasará a estado INACTIVO y se ocultará de la lista.",
            "type" => "warning",
            "id" => $id,
            "method" => "eliminarEspacio",
        ]);
    }

    #[On("eliminarEspacioConfirmado")]
    public function eliminarEspacio($id)
    {
        $espacio = Espacio::find($id);
        if (!$espacio) {
            return;
        }

        // SIEMPRE cambiar estado a inactivo
        $espacio->update(["estado" => "inactivo"]);

        $this->dispatch("swal:success", [
            "title" => "Deshabilitado",
            "text" => "El espacio ha sido archivado correctamente.",
        ]);

        $this->dispatch("espacioCreado"); // Refrescar grid (que filtra los inactivos)
    }

    // --- Lógica Crear ---

    public function abrirModalCrear()
    {
        $this->reset("nuevoEspacio");
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
            "nuevoEspacio.tipo_espacio_id" =>
                "required|exists:tipo_espacios,id",
        ]);

        $piso = Piso::find($this->pisoId);
        $tipo = TipoEspacio::find($this->nuevoEspacio["tipo_espacio_id"]);

        $letra = match ($tipo->nombre) {
            "Auto normal" => "A",
            "Moto" => "M",
            "Discapacitado" => "D",
            "Camiones" => "C",
            default => strtoupper(substr($tipo->nombre, 0, 1)),
        };

        // Contamos todos para mantener correlativo
        $cantidadExistente = Espacio::where("piso_id", $this->pisoId)
            ->where("tipo_espacio_id", $tipo->id)
            ->count();

        $correlativo = str_pad($cantidadExistente + 1, 2, "0", STR_PAD_LEFT);
        $codigoGenerado = "P{$piso->numero}-{$letra}{$correlativo}";

        Espacio::create([
            "codigo" => $codigoGenerado,
            "tipo_espacio_id" => $this->nuevoEspacio["tipo_espacio_id"],
            "estado" => "libre",
            "piso_id" => $this->pisoId,
        ]);

        $this->cerrarModal();
        $this->dispatch("espacioCreado");

        $this->dispatch("swal:success", [
            "title" => "¡Creado!",
            "text" => "Espacio {$codigoGenerado} listo para usar.",
        ]);
    }

    public function render()
    {
        if (!$this->pisoId) {
            $this->pisoId =
                Piso::where("numero", 1)->first()?->id ?? Piso::first()?->id;
        }

        // Filtramos 'inactivo' para que no aparezcan en la lista
        $query = Espacio::where("piso_id", $this->pisoId);
        if ($this->filtroEstado !== "todos") {
            $query->where("estado", $this->filtroEstado);
        }

        if ($this->filtroTipo !== "todos") {
            $query->where("tipo_espacio_id", $this->filtroTipo);
        }

        if (!empty($this->busqueda)) {
            $query->where(function ($q) {
                $q->where("codigo", "LIKE", "%{$this->busqueda}%")->orWhereHas(
                    "ticketActivo",
                    fn($sub) => $sub->where(
                        "placa",
                        "LIKE",
                        "%{$this->busqueda}%",
                    ),
                );
            });
        }

        return view("livewire.espacios-list", [
            "espacios" => $query
                ->with("ticketActivo", "tipoEspacio")
                ->orderBy("codigo")
                ->get(),
            "pisoId" => $this->pisoId,
        ]);
    }
}
