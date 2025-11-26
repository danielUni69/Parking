<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">
            <i class="fas fa-chart-line text-yellow-500 mr-3"></i>
            Reportes de Estacionamiento
        </h1>
        <p class="text-gray-400">Genera reportes detallados y descárgalos en formato PDF</p>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 mb-6 shadow-xl">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-filter text-yellow-500 mr-2"></i>
            Filtros de Búsqueda
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Fecha Inicio -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i> Fecha Inicio
                </label>
                <input type="date" wire:model="fechaInicio"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                @error('fechaInicio')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
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
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
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
                    <i class="fas fa-info-circle mr-1"></i> Estado
                </label>
                <select wire:model="estado"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                    <option value="todos">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="pagado">Pagado</option>
                </select>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex flex-wrap gap-3">
            <button wire:click="generarReporte"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all shadow-lg hover:shadow-xl flex items-center">
                <i class="fas fa-search mr-2" wire:loading.remove wire:target="generarReporte"></i>
                <i class="fas fa-spinner fa-spin mr-2" wire:loading wire:target="generarReporte"></i>
                <span wire:loading.remove wire:target="generarReporte">Generar Reporte</span>
                <span wire:loading wire:target="generarReporte">Generando...</span>
            </button>

            @if($mostrarResultados)
                <button wire:click="descargarPDF"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                    class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-xl flex items-center">
                    <i class="fas fa-file-pdf mr-2" wire:loading.remove wire:target="descargarPDF"></i>
                    <i class="fas fa-spinner fa-spin mr-2" wire:loading wire:target="descargarPDF"></i>
                    <span wire:loading.remove wire:target="descargarPDF">Descargar PDF</span>
                    <span wire:loading wire:target="descargarPDF">Descargando...</span>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Total Ingresos -->
            <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-xl p-6 border border-green-700 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-300 text-sm font-medium">Total Ingresos</p>
                        <p class="text-3xl font-bold text-white mt-2">Bs. {{ number_format($estadisticas['totalIngresos'], 2) }}</p>
                        <p class="text-green-400 text-xs mt-1">{{ $estadisticas['ticketsPagados'] }} pagados</p>
                    </div>
                    <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Tickets -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-xl p-6 border border-blue-700 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-300 text-sm font-medium">Total Registros</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $estadisticas['cantidadTickets'] }}</p>
                        <p class="text-blue-400 text-xs mt-1">En el período</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-car text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Tickets Activos -->
            <div class="bg-gradient-to-br from-orange-900 to-orange-800 rounded-xl p-6 border border-orange-700 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-300 text-sm font-medium">Vehículos Activos</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $estadisticas['ticketsActivos'] }}</p>
                        <p class="text-orange-400 text-xs mt-1">Sin salir aún</p>
                    </div>
                    <div class="w-14 h-14 bg-orange-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-2xl"></i>
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
                        <div class="bg-gradient-to-br from-gray-700 to-gray-750 rounded-lg p-5 border border-gray-600 hover:border-yellow-500 transition-all transform hover:scale-105">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-gray-400 text-sm font-medium">{{ $tipo }}</p>
                                @if($tipo === 'Auto normal')
                                    <i class="fas fa-car text-blue-400 text-lg"></i>
                                @elseif($tipo === 'Moto')
                                    <i class="fas fa-motorcycle text-orange-400 text-lg"></i>
                                @elseif($tipo === 'Discapacitado')
                                    <i class="fas fa-wheelchair text-purple-400 text-lg"></i>
                                @else
                                    <i class="fas fa-truck text-red-400 text-lg"></i>
                                @endif
                            </div>
                            <p class="text-2xl font-bold text-white mb-1">Bs. {{ number_format($datos['total'], 2) }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-gray-400 text-xs">{{ $datos['cantidad'] }} vehículos</p>
                                <span class="text-yellow-500 text-xs font-semibold">
                                    {{ $estadisticas['totalIngresos'] > 0 ? round(($datos['total'] / $estadisticas['totalIngresos']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Tabla de Registros -->
        <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-list text-yellow-500 mr-2"></i>
                    Registros de Estacionamiento
                </h3>
                <span class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg font-semibold text-sm">
                    {{ $registros->count() }} registros
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-700">
                            <th class="text-left py-4 px-4 text-gray-300 font-semibold text-sm">
                                <i class="fas fa-sign-in-alt mr-1 text-blue-400"></i> Fecha Ingreso
                            </th>
                            <th class="text-left py-4 px-4 text-gray-300 font-semibold text-sm">
                                <i class="fas fa-sign-out-alt mr-1 text-green-400"></i> Fecha Salida/Pago
                            </th>
                            <th class="text-left py-4 px-4 text-gray-300 font-semibold text-sm">
                                <i class="fas fa-id-card mr-1 text-yellow-400"></i> Placa
                            </th>
                            <th class="text-left py-4 px-4 text-gray-300 font-semibold text-sm">
                                <i class="fas fa-parking mr-1 text-purple-400"></i> Tipo Espacio
                            </th>
                            <th class="text-left py-4 px-4 text-gray-300 font-semibold text-sm">
                                <i class="fas fa-map-marker-alt mr-1 text-red-400"></i> Espacio
                            </th>
                            <th class="text-center py-4 px-4 text-gray-300 font-semibold text-sm">
                                <i class="fas fa-info-circle mr-1 text-blue-400"></i> Estado
                            </th>
                            <th class="text-right py-4 px-4 text-gray-300 font-semibold text-sm">
                                <i class="fas fa-money-bill-wave mr-1 text-green-400"></i> Monto
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $registro)
                            <tr class="border-b border-gray-700 hover:bg-gray-750 transition-colors">
                                <td class="py-4 px-4 text-gray-300">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($registro->horaIngreso)->format('d/m/Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($registro->horaIngreso)->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-300">
                                    @if($registro->horaSalida)
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($registro->horaSalida)->format('d/m/Y') }}</span>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($registro->horaSalida)->format('H:i') }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-500 italic">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-white font-bold text-base">{{ $registro->placa }}</span>
                                </td>
                                <td class="py-4 px-4 text-gray-300">
                                    {{ $registro->espacio->tipoEspacio->nombre ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 bg-gray-700 text-gray-300 rounded-lg text-sm font-medium">
                                        {{ $registro->espacio->codigo ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($registro->estado === 'activo')
                                        <span class="px-3 py-1 bg-orange-900 text-orange-300 rounded-full text-xs font-medium inline-flex items-center">
                                            <i class="fas fa-clock mr-1"></i> Activo
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-green-900 text-green-300 rounded-full text-xs font-medium inline-flex items-center">
                                            <i class="fas fa-check-circle mr-1"></i> Pagado
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    @if($registro->pago)
                                        <span class="text-green-400 font-bold text-base">Bs. {{ number_format($registro->pago->monto, 2) }}</span>
                                    @else
                                        <span class="text-gray-500 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-gray-600 text-6xl mb-4"></i>
                                        <p class="text-gray-400 text-lg font-medium">No se encontraron registros</p>
                                        <p class="text-gray-500 text-sm mt-1">Intenta ajustar los filtros de búsqueda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($registros->count() > 0)
                        <tfoot>
                            <tr class="border-t-2 border-yellow-500 bg-gradient-to-r from-gray-750 to-gray-800">
                                <td colspan="6" class="py-5 px-4 text-right">
                                    <span class="text-white font-bold text-lg">
                                        <i class="fas fa-calculator mr-2 text-yellow-500"></i>
                                        TOTAL INGRESOS:
                                    </span>
                                </td>
                                <td class="py-5 px-4 text-right">
                                    <span class="text-green-400 font-bold text-2xl">
                                        Bs. {{ number_format($estadisticas['totalIngresos'], 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            @if($registros->count() > 0)
                <!-- Resumen adicional debajo de la tabla -->
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-750 rounded-lg p-4 border border-gray-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-xs">Tickets Pagados</p>
                                    <p class="text-xl font-bold text-green-400">{{ $estadisticas['ticketsPagados'] }}</p>
                                </div>
                                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-750 rounded-lg p-4 border border-gray-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-xs">Tickets Activos</p>
                                    <p class="text-xl font-bold text-orange-400">{{ $estadisticas['ticketsActivos'] }}</p>
                                </div>
                                <i class="fas fa-clock text-orange-500 text-2xl"></i>
                            </div>
                        </div>
                        <div class="bg-gray-750 rounded-lg p-4 border border-gray-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-xs">Ingreso Promedio</p>
                                    <p class="text-xl font-bold text-purple-400">Bs. {{ number_format($estadisticas['promedioIngreso'], 2) }}</p>
                                </div>
                                <i class="fas fa-chart-bar text-purple-500 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
