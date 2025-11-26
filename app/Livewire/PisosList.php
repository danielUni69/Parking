<?php

namespace App\Livewire;

use App\Models\Piso;
use App\Models\Espacio;
use Livewire\Component;

class PisosList extends Component
{
    public $pisos;
    public $pisoSeleccionado = null;

    // Para Crear Piso
    public $mostrarModal = false;
    public $nuevoPisoNumero;
    public $nuevoPisoDescripcion;

    public function mount()
    {
        $this->cargarPisos();
        if (!$this->pisoSeleccionado && $this->pisos->isNotEmpty()) {
            $this->seleccionarPiso($this->pisos->first()->id);
        }
    }

    public function cargarPisos()
    {
        $this->pisos = Piso::orderBy("numero")->get();
    }

    public function seleccionarPiso($pisoId)
    {
        $this->pisoSeleccionado = $pisoId;
        $this->dispatch("pisoSeleccionado", $pisoId);
    }

    public function abrirModal()
    {
        $this->reset(["nuevoPisoNumero", "nuevoPisoDescripcion"]);
        $this->nuevoPisoNumero = Piso::max("numero") + 1;
        $this->mostrarModal = true;
    }

    public function crearPiso()
    {
        $this->validate([
            "nuevoPisoNumero" => "required|integer|unique:pisos,numero",
            "nuevoPisoDescripcion" => "nullable|string|max:255",
        ]);

        Piso::create([
            "numero" => $this->nuevoPisoNumero,
            "descripcion" => $this->nuevoPisoDescripcion,
        ]);

        $this->cargarPisos();
        $this->mostrarModal = false;

        if ($this->pisos->count() === 1) {
            $this->seleccionarPiso($this->pisos->first()->id);
        }

        // Emitimos evento global para que EspaciosList (o un script global) lo capture si quisieras
        // Pero aquí usamos dispatch browser event para simplificar si tuvieras listeners en este componente
        $this->dispatch("swal:success_piso", "Piso creado correctamente");
    }

    public function intentarEliminarPiso($id)
    {
        $piso = Piso::find($id);

        // Verificar si tiene espacios (incluso los inactivos)
        if (Espacio::where("piso_id", $id)->exists()) {
            $this->dispatch(
                "swal:error_piso",
                "El piso tiene espacios asociados. Elimina o deshabilita los espacios primero.",
            );
            return;
        }

        // Si está vacío, confirmamos
        $this->dispatch("swal:confirm_piso", $id);
    }

    #[\Livewire\Attributes\On("eliminarPisoConfirmado")]
    public function eliminarPiso($id)
    {
        Piso::destroy($id);
        $this->cargarPisos();

        if ($this->pisoSeleccionado == $id) {
            $nuevo = $this->pisos->first();
            $this->seleccionarPiso($nuevo ? $nuevo->id : null);
        }

        $this->dispatch("swal:success_piso", "Piso eliminado");
    }

    public function render()
    {
        return view("livewire.pisos-list");
    }
}
