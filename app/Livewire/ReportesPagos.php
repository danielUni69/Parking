<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Pago;
use App\Models\Ticket;
use App\Models\TipoEspacio;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportesPagos extends Component
{
    // Filtros
    public $fechaInicio;
    public $fechaFin;
    public $tipoEspacio = "todos";
    public $estado = "todos";

    // Datos
    public $registros;
    public $estadisticas = [];
    public $tiposEspacios;

    // UI
    public $mostrarResultados = false;

    public function mount()
    {
        // Inicializar fechas con el mes actual
        $this->fechaInicio = Carbon::now()->startOfMonth()->format("Y-m-d");
        $this->fechaFin = Carbon::now()->format("Y-m-d");

        // Cargar tipos de espacios
        $this->tiposEspacios = TipoEspacio::all();
    }

    public function generarReporte()
    {
        $this->validate(
            [
                "fechaInicio" => "required|date",
                "fechaFin" => "required|date|after_or_equal:fechaInicio",
            ],
            [
                "fechaInicio.required" => "La fecha de inicio es requerida",
                "fechaFin.required" => "La fecha de fin es requerida",
                "fechaFin.after_or_equal" =>
                    "La fecha fin debe ser posterior a la fecha inicio",
            ],
        );

        // Query de tickets con sus pagos
        $query = Ticket::with([
            "espacio.tipoEspacio",
            "usuario",
            "pago",
        ])->whereBetween("horaIngreso", [
            $this->fechaInicio . " 00:00:00",
            $this->fechaFin . " 23:59:59",
        ]);

        // Filtrar por tipo de espacio
        if ($this->tipoEspacio !== "todos") {
            $query->whereHas("espacio", function ($q) {
                $q->where("tipo_espacio_id", $this->tipoEspacio);
            });
        }

        // Filtrar por estado
        if ($this->estado !== "todos") {
            $query->where("estado", $this->estado);
        }

        $this->registros = $query->orderBy("horaIngreso", "desc")->get();

        // Calcular estadísticas
        $this->calcularEstadisticas();

        $this->mostrarResultados = true;
    }

    private function calcularEstadisticas()
    {
        // Total de ingresos (solo tickets pagados)
        $totalIngresos = $this->registros
            ->where("estado", "finalizado")
            ->sum(function ($ticket) {
                return $ticket->pago ? $ticket->pago->monto : 0;
            });

        // Cantidad de tickets por estado
        $ticketsActivos = $this->registros->where("estado", "activo")->count();
        $ticketsPagados = $this->registros->where("estado", "pagado")->count();

        // Ingresos por tipo de espacio
        $ingresosPorTipo = $this->registros
            ->where("estado", "pagado")
            ->groupBy(function ($ticket) {
                return $ticket->espacio->tipoEspacio->nombre ?? "N/A";
            })
            ->map(function ($group) {
                return [
                    "cantidad" => $group->count(),
                    "total" => $group->sum(function ($ticket) {
                        return $ticket->pago ? $ticket->pago->monto : 0;
                    }),
                ];
            });

        // Promedio de ingresos
        $promedioIngreso =
            $ticketsPagados > 0 ? $totalIngresos / $ticketsPagados : 0;

        $this->estadisticas = [
            "totalIngresos" => $totalIngresos,
            "cantidadTickets" => $this->registros->count(),
            "ticketsActivos" => $ticketsActivos,
            "ticketsPagados" => $ticketsPagados,
            "promedioIngreso" => $promedioIngreso,
            "ingresosPorTipo" => $ingresosPorTipo,
        ];
    }

    public function descargarPDF()
    {
        if (!$this->mostrarResultados) {
            $this->generarReporte();
        }

        $data = [
            "fechaInicio" => $this->fechaInicio,
            "fechaFin" => $this->fechaFin,
            "registros" => $this->registros,
            "estadisticas" => $this->estadisticas,
            "fechaGeneracion" => Carbon::now()->format("d/m/Y H:i:s"),
            "filtroTipoEspacio" =>
                $this->tipoEspacio !== "todos"
                    ? $this->tiposEspacios->find($this->tipoEspacio)->nombre
                    : "Todos",
            "filtroEstado" =>
                $this->estado !== "todos" ? ucfirst($this->estado) : "Todos",
        ];

        $pdf = Pdf::loadView("reportes.pdf", $data)
            ->setPaper("a4", "landscape")
            ->setOptions([
                "isHtml5ParserEnabled" => true,
                "isRemoteEnabled" => true,
            ]);

        $nombreArchivo =
            "reporte_parking_" . Carbon::now()->format("Y-m-d_His") . ".pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $nombreArchivo);
    }

    public function limpiarFiltros()
    {
        $this->fechaInicio = Carbon::now()->startOfMonth()->format("Y-m-d");
        $this->fechaFin = Carbon::now()->format("Y-m-d");
        $this->tipoEspacio = "todos";
        $this->estado = "todos";
        $this->mostrarResultados = false;
        $this->registros = [];
        $this->estadisticas = [];
    }

    public function render()
    {
        return view("livewire.reportes-pagos");
    }
}
