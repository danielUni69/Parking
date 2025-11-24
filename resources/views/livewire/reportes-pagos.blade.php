<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">
            <i class="fas fa-chart-line text-yellow-500 mr-3"></i>
            Reportes de Pagos y Tickets
        </h1>
        <p class="text-gray-400">Genera reportes detallados y descárgalos en formato PDF</p>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 mb-6 shadow-xl">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-filter text-yellow-500 mr-2"></i>
            Filtros de Búsqueda
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Fecha Inicio -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i> Fecha Inicio
                </label>
                <input type="date" wire:model="fechaInicio"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                @error('fechaInicio')
                    <span class="text-red-400 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Fecha Fin -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">
                    <i class="fas fa-calendar-check mr-1"></i> Fecha Fin
                </label>
                <input type="date" wire:model="fechaFin"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                @error('fechaFin')
                    <span class="text-red-400 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tipo de Espacio -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">
                    <i class="fas fa-parking mr-1"></i> Tipo de Espacio
                </label>
                <select wire:model="tipoEspacio"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                    <option value="todos">Todos los tipos</option>
                    @foreach($tiposEspacios as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Estado de Ticket -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">
                    <i class="fas fa-ticket-alt mr-1"></i> Estado de Ticket
                </label>
                <select wire:model="estado"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                    <option value="todos">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="pagado">Pagado</option>
                </select>
            </div>

            <!-- Tipo de Reporte -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">
                    <i class="fas fa-file-alt mr-1"></i> Tipo de Reporte
                </label>
                <select wire:model="tipoReporte"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                    <option value="completo">Reporte Completo</option>
                    <option value="pagos">Solo Pagos</option>
                    <option value="tickets">Solo Tickets</option>
                </select>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex flex-wrap gap-3">
            <button wire:click="generarReporte"
                class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg hover:shadow-xl flex items-center">
                <i class="fas fa-search mr-2"></i>
                Generar Reporte
            </button>

            @if($mostrarResultados)
                <button wire:click="descargarPDF"
                    class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-xl flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </button>
            @endif

            <button wire:click="limpiarFiltros"
                class="px-6 py-3 bg-gray-700 text-gray-300 font-semibold rounded-lg hover:bg-gray-600 transition-all flex items-center border border-gray-600">
                <i class="fas fa-eraser mr-2"></i>
                Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- Resultados -->
    @if($mostrarResultados)
        <!-- Estadísticas Generales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Ingresos -->
            <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-xl p-6 border border-green-700 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-300 text-sm font-medium">Total Ingresos</p>
                        <p class="text-3xl font-bold text-white mt-2">Bs. {{ number_format($estadisticas['totalIngresos'], 2) }}</p>
                    </div>
                    <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Tickets -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-xl p-6 border border-blue-700 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-300 text-sm font-medium">Total Tickets</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $estadisticas['cantidadTickets'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Tickets Activos -->
            <div class="bg-gradient-to-br from-orange-900 to-orange-800 rounded-xl p-6 border border-orange-700 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-300 text-sm font-medium">Tickets Activos</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $estadisticas['ticketsActivos'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-orange-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Promedio Ingreso -->
            <div class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-xl p-6 border border-purple-700 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-300 text-sm font-medium">Promedio por Ticket</p>
                        <p class="text-3xl font-bold text-white mt-2">Bs. {{ number_format($estadisticas['promedioIngreso'], 2) }}</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos por Tipo de Espacio -->
        @if($estadisticas['ingresosPorTipo']->isNotEmpty())
            <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 mb-6 shadow-xl">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-yellow-500 mr-2"></i>
                    Ingresos por Tipo de Espacio
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($estadisticas['ingresosPorTipo'] as $tipo => $datos)
                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                            <p class="text-gray-400 text-sm mb-1">{{ $tipo }}</p>
                            <p class="text-2xl font-bold text-white">Bs. {{ number_format($datos['total'], 2) }}</p>
                            <p class="text-gray-400 text-xs mt-1">{{ $datos['cantidad'] }} tickets</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Tabla de Tickets -->
        @if($tipoReporte === 'completo' || $tipoReporte === 'tickets')
            <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 mb-6 shadow-xl">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-list text-yellow-500 mr-2"></i>
                    Listado de Tickets ({{ $tickets->count() }})
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">ID</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Placa</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Tipo Espacio</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Espacio</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Ingreso</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Salida</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Estado</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="border-b border-gray-700 hover:bg-gray-750 transition">
                                    <td class="py-3 px-4 text-gray-300">#{{ $ticket->id }}</td>
                                    <td class="py-3 px-4 text-white font-medium">{{ $ticket->placa }}</td>
                                    <td class="py-3 px-4 text-gray-300">{{ $ticket->espacio->tipoEspacio->nombre ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-300">{{ $ticket->espacio->codigo ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-300">{{ \Carbon\Carbon::parse($ticket->horaIngreso)->format('d/m/Y H:i') }}</td>
                                    <td class="py-3 px-4 text-gray-300">
                                        {{ $ticket->horaSalida ? \Carbon\Carbon::parse($ticket->horaSalida)->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($ticket->estado === 'activo')
                                            <span class="px-3 py-1 bg-orange-900 text-orange-300 rounded-full text-xs font-medium">Activo</span>
                                        @else
                                            <span class="px-3 py-1 bg-green-900 text-green-300 rounded-full text-xs font-medium">Pagado</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-white font-semibold">
                                        {{ $ticket->pago ? 'Bs. ' . number_format($ticket->pago->monto, 2) : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>No se encontraron tickets en este período</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tabla de Pagos -->
        @if($tipoReporte === 'completo' || $tipoReporte === 'pagos')
            <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 shadow-xl">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-money-check-alt text-yellow-500 mr-2"></i>
                    Listado de Pagos ({{ $pagos->count() }})
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">ID Pago</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">ID Ticket</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Placa</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Tipo Espacio</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Fecha</th>
                                <th class="text-left py-3 px-4 text-gray-300 font-semibold text-sm">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pagos as $pago)
                                <tr class="border-b border-gray-700 hover:bg-gray-750 transition">
                                    <td class="py-3 px-4 text-gray-300">#{{ $pago->id }}</td>
                                    <td class="py-3 px-4 text-gray-300">#{{ $pago->ticket_id }}</td>
                                    <td class="py-3 px-4 text-white font-medium">{{ $pago->ticket->placa ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-300">{{ $pago->ticket->espacio->tipoEspacio->nombre ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-300">{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') }}</td>
                                    <td class="py-3 px-4 text-green-400 font-bold">Bs. {{ number_format($pago->monto, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-2"></i>
                                        <p>No se encontraron pagos en este período</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-yellow-500">
                                <td colspan="5" class="py-4 px-4 text-right text-white font-bold text-lg">TOTAL:</td>
                                <td class="py-4 px-4 text-green-400 font-bold text-xl">Bs. {{ number_format($pagos->sum('monto'), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
