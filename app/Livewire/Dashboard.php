<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Espacio;
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

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        // Total de espacios
        $this->totalEspacios = Espacio::count();

        // Espacios disponibles
        $this->espaciosDisponibles = Espacio::where('estado', 'libre')->count();

        // Espacios ocupados
        $this->espaciosOcupados = Espacio::where('estado', 'ocupado')->count();

        // Ingresos del día
        $this->ingresosHoy = Pago::whereDate('created_at', today())->sum('monto');

        // Actividad reciente (últimos 5 tickets)
        $this->actividadReciente = Ticket::with(['espacio', 'pago'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
