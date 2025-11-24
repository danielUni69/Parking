<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pagos y Tickets</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #fbbf24;
        }

        .header .subtitle {
            font-size: 12px;
            color: #d1d5db;
        }

        .info-box {
            background: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #fbbf24;
            border-radius: 4px;
        }

        .info-box h3 {
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #4b5563;
        }

        .info-value {
            color: #1f2937;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .stat-card.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .stat-label {
            font-size: 10px;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
        }

        table thead {
            background: #1f2937;
            color: white;
        }

        table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }

        table tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-active {
            background: #fed7aa;
            color: #9a3412;
        }

        .badge-paid {
            background: #bbf7d0;
            color: #166534;
        }

        .total-row {
            background: #fef3c7;
            font-weight: bold;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 10px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #fbbf24;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
            font-style: italic;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🦙 ESTACIONAMIENTO JEMITA</h1>
        <div class="subtitle">Reporte de {{ ucfirst($tipoReporte) }} - Sistema de Gestión</div>
    </div>

    <!-- Información del Reporte -->
    <div class="info-box">
        <h3>📋 Información del Reporte</h3>
        <div class="info-row">
            <span class="info-label">Período:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span class="info-value">{{ $fechaGeneracion }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tipo de Reporte:</span>
            <span class="info-value">{{ ucfirst($tipoReporte) }}</span>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Ingresos</div>
            <div class="stat-value">Bs. {{ number_format($estadisticas['totalIngresos'], 2) }}</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Total Tickets</div>
            <div class="stat-value">{{ $estadisticas['cantidadTickets'] }}</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-label">Tickets Activos</div>
            <div class="stat-value">{{ $estadisticas['ticketsActivos'] }}</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Promedio por Ticket</div>
            <div class="stat-value">Bs. {{ number_format($estadisticas['promedioIngreso'], 2) }}</div>
        </div>
    </div>

    <!-- Ingresos por Tipo de Espacio -->
    @if($estadisticas['ingresosPorTipo']->isNotEmpty())
        <div class="section-title">💰 Ingresos por Tipo de Espacio</div>
        <table>
            <thead>
                <tr>
                    <th>Tipo de Espacio</th>
                    <th style="text-align: center;">Cantidad de Tickets</th>
                    <th style="text-align: right;">Total Ingresos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estadisticas['ingresosPorTipo'] as $tipo => $datos)
                    <tr>
                        <td><strong>{{ $tipo }}</strong></td>
                        <td style="text-align: center;">{{ $datos['cantidad'] }}</td>
                        <td style="text-align: right;"><strong>Bs. {{ number_format($datos['total'], 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Listado de Tickets -->
    @if($tipoReporte === 'completo' || $tipoReporte === 'tickets')
        <div class="section-title">🎫 Listado de Tickets ({{ $tickets->count() }})</div>
        @if($tickets->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Placa</th>
                        <th>Tipo Espacio</th>
                        <th>Espacio</th>
                        <th>Ingreso</th>
                        <th>Salida</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td><strong>{{ $ticket->placa }}</strong></td>
                            <td>{{ $ticket->espacio->tipoEspacio->nombre ?? 'N/A' }}</td>
                            <td>{{ $ticket->espacio->codigo ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->horaIngreso)->format('d/m/Y H:i') }}</td>
                            <td>{{ $ticket->horaSalida ? \Carbon\Carbon::parse($ticket->horaSalida)->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                @if($ticket->estado === 'activo')
                                    <span class="badge badge-active">Activo</span>
                                @else
                                    <span class="badge badge-paid">Pagado</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                {{ $ticket->pago ? 'Bs. ' . number_format($ticket->pago->monto, 2) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No se encontraron tickets en este período</div>
        @endif
    @endif

    <!-- Listado de Pagos -->
    @if($tipoReporte === 'completo' || $tipoReporte === 'pagos')
        <div class="page-break"></div>
        <div class="section-title">💵 Listado de Pagos ({{ $pagos->count() }})</div>
        @if($pagos->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID Pago</th>
                        <th>ID Ticket</th>
                        <th>Placa</th>
                        <th>Tipo Espacio</th>
                        <th>Fecha</th>
                        <th style="text-align: right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                        <tr>
                            <td>#{{ $pago->id }}</td>
                            <td>#{{ $pago->ticket_id }}</td>
                            <td><strong>{{ $pago->ticket->placa ?? 'N/A' }}</strong></td>
                            <td>{{ $pago->ticket->espacio->tipoEspacio->nombre ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') }}</td>
                            <td style="text-align: right;"><strong>Bs. {{ number_format($pago->monto, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="5" style="text-align: right; padding: 12px;"><strong>TOTAL GENERAL:</strong></td>
                        <td style="text-align: right; padding: 12px;"><strong>Bs. {{ number_format($pagos->sum('monto'), 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="no-data">No se encontraron pagos en este período</div>
        @endif
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Estacionamiento JEMITA</strong> - Sistema de Gestión de Estacionamientos</p>
        <p>Generado automáticamente el {{ $fechaGeneracion }}</p>
        <p>🦙 Este documento es válido como comprobante de ingresos</p>
    </div>
</body>
</html>
