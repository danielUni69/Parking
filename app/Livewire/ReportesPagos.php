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
    public $tipoReporte = "completo"; // completo, pagos, tickets

    // Datos
    public $tickets;
    public $pagos;
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

        // Query base de tickets
        $queryTickets = Ticket::with([
            "espacio.tipoEspacio",
            "usuario",
            "pago",
        ])->whereBetween("horaIngreso", [
            $this->fechaInicio . " 00:00:00",
            $this->fechaFin . " 23:59:59",
        ]);

        // Filtrar por tipo de espacio
        if ($this->tipoEspacio !== "todos") {
            $queryTickets->whereHas("espacio", function ($query) {
                $query->where("tipo_espacio_id", $this->tipoEspacio);
            });
        }

        // Filtrar por estado
        if ($this->estado !== "todos") {
            $queryTickets->where("estado", $this->estado);
        }

        $this->tickets = $queryTickets->orderBy("horaIngreso", "desc")->get();

        // Query de pagos
        $queryPagos = Pago::with(["ticket.espacio.tipoEspacio"])->whereBetween(
            "fecha",
            [$this->fechaInicio . " 00:00:00", $this->fechaFin . " 23:59:59"],
        );

        if ($this->tipoEspacio !== "todos") {
            $queryPagos->whereHas("ticket.espacio", function ($query) {
                $query->where("tipo_espacio_id", $this->tipoEspacio);
            });
        }

        $this->pagos = $queryPagos->orderBy("fecha", "desc")->get();

        // Calcular estadísticas
        $this->calcularEstadisticas();

        $this->mostrarResultados = true;
    }

    private function calcularEstadisticas()
    {
        // Total de ingresos
        $totalIngresos = $this->pagos->sum("monto");

        // Cantidad de tickets por estado
        $ticketsActivos = $this->tickets->where("estado", "activo")->count();
        $ticketsPagados = $this->tickets->where("estado", "pagado")->count();

        // Ingresos por tipo de espacio
        $ingresosPorTipo = $this->pagos
            ->groupBy("ticket.espacio.tipoEspacio.nombre")
            ->map(function ($group) {
                return [
                    "cantidad" => $group->count(),
                    "total" => $group->sum("monto"),
                ];
            });

        // Promedio de ingresos
        $promedioIngreso =
            $this->pagos->count() > 0
                ? $totalIngresos / $this->pagos->count()
                : 0;

        // Ticket con mayor monto
        $ticketMayorMonto = $this->pagos->sortByDesc("monto")->first();

        $this->estadisticas = [
            "totalIngresos" => $totalIngresos,
            "cantidadTickets" => $this->tickets->count(),
            "ticketsActivos" => $ticketsActivos,
            "ticketsPagados" => $ticketsPagados,
            "cantidadPagos" => $this->pagos->count(),
            "promedioIngreso" => $promedioIngreso,
            "ingresosPorTipo" => $ingresosPorTipo,
            "ticketMayorMonto" => $ticketMayorMonto,
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
            "tickets" => $this->tickets,
            "pagos" => $this->pagos,
            "estadisticas" => $this->estadisticas,
            "tipoReporte" => $this->tipoReporte,
            "fechaGeneracion" => Carbon::now()->format("d/m/Y H:i:s"),
        ];

        $pdf = Pdf::loadView("reportes.pdf", $data)
            ->setPaper("a4", "portrait")
            ->setOptions([
                "isHtml5ParserEnabled" => true,
                "isRemoteEnabled" => true,
            ]);

        $nombreArchivo =
            "reporte_" .
            $this->tipoReporte .
            "_" .
            Carbon::now()->format("Y-m-d_His") .
            ".pdf";

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
        $this->tipoReporte = "completo";
        $this->mostrarResultados = false;
        $this->tickets = [];
        $this->pagos = [];
        $this->estadisticas = [];
    }

    public function render()
    {
        return view("livewire.reportes-pagos");
    }
}
