<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Espacio;
use App\Models\TipoEspacio;
use App\Models\Piso;
use App\Models\Ticket;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalEspacios;
    public $espaciosDisponibles;
    public $espaciosOcupados;
    public $ingresosHoy;
    public $actividadReciente;
    public $tiposEspacios;
    public $espaciosPorTipo;

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        // Total de espacios
        $this->totalEspacios = Espacio::count();

        // Espacios disponibles
        $this->espaciosDisponibles = Espacio::where("estado", "libre")->count();

        // Espacios ocupados
        $this->espaciosOcupados = Espacio::where("estado", "ocupado")->count();

        // Ingresos del día
        $this->ingresosHoy = Pago::whereDate("created_at", today())->sum(
            "monto",
        );

        // Actividad reciente (últimos 5 tickets)
        $this->actividadReciente = Ticket::with(["espacio", "pago"])
            ->orderBy("created_at", "desc")
            ->limit(5)
            ->get();

        // Obtener todos los tipos de espacios con sus tarifas
        $this->tiposEspacios = TipoEspacio::all();

        // Contar espacios disponibles por tipo
        $this->espaciosPorTipo = Espacio::select(
            "tipo_espacio_id",
            DB::raw("count(*) as total"),
        )
            ->where("estado", "libre")
            ->groupBy("tipo_espacio_id")
            ->pluck("total", "tipo_espacio_id")
            ->toArray();
    }

    public function render()
    {
        return view("livewire.dashboard");
    }
}
