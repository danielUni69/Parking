<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Piso;
use App\Models\Espacio;
use App\Models\TipoEspacio;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportesPisos extends Component
{
    // Filtros
    public $pisoSeleccionado = "todos";
    public $tipoEspacio = "todos";
    public $estadoEspacio = "todos";

    // Datos
    public $pisos;
    public $tiposEspacios;
    public $estadisticas = [];
    public $espaciosPorPiso = [];

    // UI
    public $mostrarResultados = false;

    public function mount()
    {
        // Cargar pisos y tipos de espacios
        $this->pisos = Piso::orderBy("numero")->get();
        $this->tiposEspacios = TipoEspacio::all();
    }

    public function generarReporte()
    {
        // Query base de pisos con sus espacios
        $query = Piso::with([
            "espacios.tipoEspacio",
            "espacios.ticketActivo",
        ])->orderBy("numero");

        // Filtrar por piso específico
        if ($this->pisoSeleccionado !== "todos") {
            $query->where("id", $this->pisoSeleccionado);
        }

        $pisos = $query->get();

        // Procesar cada piso
        $espaciosPorPisoArray = [];

        foreach ($pisos as $piso) {
            $espacios = $piso->espacios;

            // Filtrar por tipo de espacio
            if ($this->tipoEspacio !== "todos") {
                $espacios = $espacios->where(
                    "tipo_espacio_id",
                    $this->tipoEspacio,
                );
            }

            // Filtrar por estado
            if ($this->estadoEspacio !== "todos") {
                $espacios = $espacios->where("estado", $this->estadoEspacio);
            }

            $total = $espacios->count();

            // Solo agregar pisos con espacios
            if ($total > 0) {
                $libres = $espacios->where("estado", "libre")->count();
                $ocupados = $espacios->where("estado", "ocupado")->count();
                $inactivos = $espacios->where("estado", "inactivo")->count();

                $espaciosPorPisoArray[] = [
                    "piso" => $piso,
                    "espacios" => $espacios,
                    "total" => $total,
                    "libres" => $libres,
                    "ocupados" => $ocupados,
                    "inactivos" => $inactivos,
                    "porcentajeOcupacion" =>
                        $total > 0 ? round(($ocupados / $total) * 100, 1) : 0,
                ];
            }
        }

        $this->espaciosPorPiso = collect($espaciosPorPisoArray);

        // Calcular estadísticas generales
        $this->calcularEstadisticas();

        $this->mostrarResultados = true;
    }

    private function calcularEstadisticas()
    {
        // Totales generales
        $totalEspacios = $this->espaciosPorPiso->sum("total");
        $totalLibres = $this->espaciosPorPiso->sum("libres");
        $totalOcupados = $this->espaciosPorPiso->sum("ocupados");
        $totalInactivos = $this->espaciosPorPiso->sum("inactivos");

        // Porcentaje de ocupación general
        $porcentajeOcupacion =
            $totalEspacios > 0
                ? round(($totalOcupados / $totalEspacios) * 100, 1)
                : 0;

        // Espacios por tipo
        $espaciosPorTipoArray = [];

        foreach ($this->espaciosPorPiso as $pisoData) {
            foreach ($pisoData["espacios"] as $espacio) {
                $tipo = $espacio->tipoEspacio->nombre ?? "N/A";

                if (!isset($espaciosPorTipoArray[$tipo])) {
                    $espaciosPorTipoArray[$tipo] = [
                        "total" => 0,
                        "libres" => 0,
                        "ocupados" => 0,
                        "inactivos" => 0,
                    ];
                }

                $espaciosPorTipoArray[$tipo]["total"]++;

                if ($espacio->estado === "libre") {
                    $espaciosPorTipoArray[$tipo]["libres"]++;
                }
                if ($espacio->estado === "ocupado") {
                    $espaciosPorTipoArray[$tipo]["ocupados"]++;
                }
                if ($espacio->estado === "inactivo") {
                    $espaciosPorTipoArray[$tipo]["inactivos"]++;
                }
            }
        }

        $espaciosPorTipo = collect($espaciosPorTipoArray);

        // Piso con mayor ocupación
        $pisoMayorOcupacion = $this->espaciosPorPiso
            ->sortByDesc("porcentajeOcupacion")
            ->first();

        // Piso con menor ocupación (excluyendo 0%)
        $pisoMenorOcupacion = $this->espaciosPorPiso
            ->where("porcentajeOcupacion", ">", 0)
            ->sortBy("porcentajeOcupacion")
            ->first();

        $this->estadisticas = [
            "totalEspacios" => $totalEspacios,
            "totalLibres" => $totalLibres,
            "totalOcupados" => $totalOcupados,
            "totalInactivos" => $totalInactivos,
            "porcentajeOcupacion" => $porcentajeOcupacion,
            "espaciosPorTipo" => $espaciosPorTipo,
            "pisoMayorOcupacion" => $pisoMayorOcupacion,
            "pisoMenorOcupacion" => $pisoMenorOcupacion,
            "totalPisos" => $this->espaciosPorPiso->count(),
        ];
    }

    public function descargarPDF()
    {
        if (!$this->mostrarResultados) {
            $this->generarReporte();
        }

        $data = [
            "espaciosPorPiso" => $this->espaciosPorPiso,
            "estadisticas" => $this->estadisticas,
            "fechaGeneracion" => Carbon::now()->format("d/m/Y H:i:s"),
            "filtroPiso" =>
                $this->pisoSeleccionado !== "todos"
                    ? "Piso " .
                        $this->pisos->find($this->pisoSeleccionado)->numero
                    : "Todos los pisos",
            "filtroTipoEspacio" =>
                $this->tipoEspacio !== "todos"
                    ? $this->tiposEspacios->find($this->tipoEspacio)->nombre
                    : "Todos los tipos",
            "filtroEstado" =>
                $this->estadoEspacio !== "todos"
                    ? ucfirst($this->estadoEspacio)
                    : "Todos los estados",
        ];

        $pdf = Pdf::loadView("reportes.pisos-pdf", $data)
            ->setPaper("a4", "portrait")
            ->setOptions([
                "isHtml5ParserEnabled" => true,
                "isRemoteEnabled" => true,
            ]);

        $nombreArchivo =
            "reporte_pisos_" . Carbon::now()->format("Y-m-d_His") . ".pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $nombreArchivo);
    }

    public function limpiarFiltros()
    {
        $this->pisoSeleccionado = "todos";
        $this->tipoEspacio = "todos";
        $this->estadoEspacio = "todos";
        $this->mostrarResultados = false;
        $this->espaciosPorPiso = collect([]);
        $this->estadisticas = [];
    }

    public function render()
    {
        return view("livewire.reportes-pisos");
    }
}
