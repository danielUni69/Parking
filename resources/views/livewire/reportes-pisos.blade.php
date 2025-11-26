<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">
            <i class="fas fa-building text-yellow-500 mr-3"></i>
            Reportes de Pisos y Espacios
        </h1>
        <p class="text-gray-400">Visualiza la ocupación y estado de todos los espacios por piso</p>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 mb-6 shadow-xl">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-filter text-yellow-500 mr-2"></i>
            Filtros de Búsqueda
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Seleccionar Piso -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">
                    <i class="fas fa-layer-group mr-1"></i> Piso
                </label>
                <select wire:model="pisoSeleccionado"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                    <option value="todos">Todos los pisos</option>
                    @foreach($pisos as $piso)
                        <option value="{{ $piso->id }}">Piso {{ $piso->numero }}</option>
                    @endforeach
                </select>
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

            <!-- Estado del Espacio -->
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">
                    <i class="fas fa-info-circle mr-1"></i> Estado
                </label>
                <select wire:model="estadoEspacio"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
                    <option value="todos">Todos los estados</option>
                    <option value="libre">Libre</option>
                    <option value="ocupado">Ocupado</option>
                    <option value="inactivo">Inactivo</option>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Espacios -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-xl p-6 border border-blue-700 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-300 text-sm font-medium">Total Espacios</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $estadisticas['totalEspacios'] }}</p>
                        <p class="text-blue-400 text-xs mt-1">En {{ $estadisticas['totalPisos'] }} pisos</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-th text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Espacios Libres -->
            <div class="bg-gradient-to-br from-green-900 to-green-800 rounded-xl p-6 border border-green-700 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-300 text-sm font-medium">Espacios Libres</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $estadisticas['totalLibres'] }}</p>
                        <p class="text-green-400 text-xs mt-1">{{ $estadisticas['totalEspacios'] > 0 ? round(($estadisticas['totalLibres'] / $estadisticas['totalEspacios']) * 100, 1) : 0 }}% disponible</p>
                    </div>
                    <div class="w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Espacios Ocupados -->
            <div class="bg-gradient-to-br from-red-900 to-red-800 rounded-xl p-6 border border-red-700 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-300 text-sm font-medium">Espacios Ocupados</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $estadisticas['totalOcupados'] }}</p>
                        <p class="text-red-400 text-xs mt-1">{{ $estadisticas['porcentajeOcupacion'] }}% ocupación</p>
                    </div>
                    <div class="w-14 h-14 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-car text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Espacios Inactivos -->
            <div class="bg-gradient-to-br from-gray-700 to-gray-800 rounded-xl p-6 border border-gray-600 shadow-xl transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm font-medium">Espacios Inactivos</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $estadisticas['totalInactivos'] }}</p>
                        <p class="text-gray-400 text-xs mt-1">Fuera de servicio</p>
                    </div>
                    <div class="w-14 h-14 bg-gray-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-ban text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Espacios por Tipo -->
        @if($estadisticas['espaciosPorTipo']->isNotEmpty())
            <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 mb-6 shadow-xl">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-yellow-500 mr-2"></i>
                    Distribución por Tipo de Espacio
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($estadisticas['espaciosPorTipo'] as $tipo => $datos)
                        <div class="bg-gradient-to-br from-gray-700 to-gray-750 rounded-lg p-5 border border-gray-600 hover:border-yellow-500 transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-white text-sm font-bold">{{ $tipo }}</p>
                                @if($tipo === 'Auto normal')
                                    <i class="fas fa-car text-blue-400 text-xl"></i>
                                @elseif($tipo === 'Moto')
                                    <i class="fas fa-motorcycle text-orange-400 text-xl"></i>
                                @elseif($tipo === 'Discapacitado')
                                    <i class="fas fa-wheelchair text-purple-400 text-xl"></i>
                                @else
                                    <i class="fas fa-truck text-red-400 text-xl"></i>
                                @endif
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400 text-xs">Total:</span>
                                    <span class="text-white font-semibold">{{ $datos['total'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-green-400 text-xs">Libres:</span>
                                    <span class="text-green-400 font-semibold">{{ $datos['libres'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-red-400 text-xs">Ocupados:</span>
                                    <span class="text-red-400 font-semibold">{{ $datos['ocupados'] }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 text-xs">Inactivos:</span>
                                    <span class="text-gray-500 font-semibold">{{ $datos['inactivos'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Listado de Pisos -->
        <div class="space-y-6">
            @foreach($espaciosPorPiso as $pisoData)
                <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                    <!-- Header del Piso -->
                    <div class="bg-gradient-to-r from-gray-750 to-gray-800 p-6 border-b border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-building text-gray-900 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Piso {{ $pisoData['piso']->numero }}</h3>
                                    <p class="text-gray-400 text-sm">{{ $pisoData['total'] }} espacios en total</p>
                                </div>
                            </div>

                            <!-- Barra de Ocupación -->
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <p class="text-sm text-gray-400">Ocupación</p>
                                    <p class="text-3xl font-bold text-white">{{ $pisoData['porcentajeOcupacion'] }}%</p>
                                </div>
                                <div class="w-32">
                                    <div class="bg-gray-700 rounded-full h-4 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-500 to-red-500 transition-all"
                                             style="width: {{ $pisoData['porcentajeOcupacion'] }}%"></div>
                                    </div>
                                    <div class="flex justify-between mt-1 text-xs text-gray-400">
                                        <span>0%</span>
                                        <span>100%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats del Piso -->
                        <div class="grid grid-cols-3 gap-4 mt-6">
                            <div class="bg-green-900/30 rounded-lg p-3 border border-green-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-300 text-xs">Libres</p>
                                        <p class="text-2xl font-bold text-green-400">{{ $pisoData['libres'] }}</p>
                                    </div>
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                </div>
                            </div>
                            <div class="bg-red-900/30 rounded-lg p-3 border border-red-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-red-300 text-xs">Ocupados</p>
                                        <p class="text-2xl font-bold text-red-400">{{ $pisoData['ocupados'] }}</p>
                                    </div>
                                    <i class="fas fa-car text-red-500 text-xl"></i>
                                </div>
                            </div>
                            <div class="bg-gray-700/30 rounded-lg p-3 border border-gray-600">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-gray-300 text-xs">Inactivos</p>
                                        <p class="text-2xl font-bold text-gray-400">{{ $pisoData['inactivos'] }}</p>
                                    </div>
                                    <i class="fas fa-ban text-gray-500 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de Espacios -->
                    <div class="p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach($pisoData['espacios'] as $espacio)
                                @php
                                    $estadoClasses = [
                                        'libre' => 'bg-green-900 border-green-500 text-green-300',
                                        'ocupado' => 'bg-red-900 border-red-500 text-red-300',
                                        'inactivo' => 'bg-gray-700 border-gray-500 text-gray-400'
                                    ];
                                    $iconos = [
                                        'libre' => 'fa-check-circle',
                                        'ocupado' => 'fa-car',
                                        'inactivo' => 'fa-ban'
                                    ];
                                @endphp
                                <div class="relative {{ $estadoClasses[$espacio->estado] ?? 'bg-gray-700' }} rounded-lg p-4 border-2 hover:scale-105 transition-transform cursor-pointer group">
                                    <div class="text-center">
                                        <i class="fas {{ $iconos[$espacio->estado] ?? 'fa-question' }} text-2xl mb-2"></i>
                                        <p class="font-bold text-lg">{{ $espacio->codigo }}</p>
                                        <p class="text-xs mt-1 opacity-75">{{ $espacio->tipoEspacio->nombre ?? 'N/A' }}</p>
                                    </div>

                                    <!-- Tooltip con placa si está ocupado -->
                                    @if($espacio->estado === 'ocupado' && $espacio->ticketActivo)
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-10">
                                            <div class="bg-gray-900 text-white text-xs rounded-lg py-2 px-3 whitespace-nowrap shadow-xl border border-gray-700">
                                                <p class="font-semibold">Placa: {{ $espacio->ticketActivo->placa }}</p>
                                                <p class="text-gray-400">Desde: {{ \Carbon\Carbon::parse($espacio->ticketActivo->horaIngreso)->format('H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Leyenda -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-xl mt-6">
            <h4 class="text-white font-bold mb-4 flex items-center">
                <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
                Leyenda de Estados
            </h4>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-green-900 border-2 border-green-500 rounded flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-300 text-sm"></i>
                    </div>
                    <span class="text-gray-300">Espacio Libre</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-red-900 border-2 border-red-500 rounded flex items-center justify-center">
                        <i class="fas fa-car text-red-300 text-sm"></i>
                    </div>
                    <span class="text-gray-300">Espacio Ocupado</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-700 border-2 border-gray-500 rounded flex items-center justify-center">
                        <i class="fas fa-ban text-gray-400 text-sm"></i>
                    </div>
                    <span class="text-gray-300">Espacio Inactivo</span>
                </div>
            </div>
        </div>
    @endif
</div>
