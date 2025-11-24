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
        $this->totalEspacios = Espacio::count();
        $this->espaciosDisponibles = Espacio::where('estado', 'libre')->count();
        $this->espaciosOcupados = Espacio::where('estado', 'ocupado')->count();
        $this->ingresosHoy = Pago::whereDate('created_at', today())->sum('monto');
        $this->actividadReciente = Ticket::with(['espacio', 'pago'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $this->tiposEspacios = TipoEspacio::all();
        $this->espaciosPorTipo = Espacio::select(
            'tipo_espacio_id',
            DB::raw('count(*) as total')
        )
            ->where('estado', 'libre')
            ->groupBy('tipo_espacio_id')
            ->pluck('total', 'tipo_espacio_id')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
