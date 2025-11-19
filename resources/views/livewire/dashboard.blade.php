<div class="m-4">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Bienvenido al Sistema</h1>

    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Spaces -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Espacios Totales</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalEspacios }}</p>
                </div>
                <div class="w-12 h-12 gold-gradient rounded-full flex items-center justify-center">
                    <i class="fas fa-parking text-white"></i>
                </div>
            </div>
        </div>

        <!-- Available Spaces -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-green-500 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Disponibles</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $espaciosDisponibles }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-car text-white"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-green-400 text-sm">
                    <i class="fas fa-check mr-1"></i>
                    <span>{{ round(($espaciosDisponibles / $totalEspacios) * 100) }}% disponibles</span>
                </div>
            </div>
        </div>

        <!-- Occupied Spaces -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-red-500 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Ocupados</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $espaciosOcupados }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-white"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-red-400 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ round(($espaciosOcupados / $totalEspacios) * 100) }}% ocupación</span>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-blue-500 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Ingresos Hoy</p>
                    <p class="text-3xl font-bold text-white mt-2">Bs. {{ number_format($ingresosHoy, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Parking Map -->
        <div class="lg:col-span-2 bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white">Mapa de Estacionamiento</h2>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-sync-alt mr-2"></i>Actualizar
                    </button>
                    <button class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition">
                        <i class="fas fa-print mr-2"></i>Imprimir
                    </button>
                </div>
            </div>
            <!-- Aquí puedes integrar el componente EspaciosList -->
            <livewire:espacios-list />
        </div>

        <!-- Recent Activity -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <h2 class="text-xl font-bold text-white mb-6">Actividad Reciente</h2>
            <div class="space-y-4">
                @forelse($actividadReciente as $actividad)
                    <div class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-car text-white"></i>
                        </div>
                        <div>
                            <p class="text-white font-medium">
                                @if($actividad->estado === 'activo')
                                    Vehículo ingresó
                                @else
                                    Vehículo salió
                                @endif
                            </p>
                            <p class="text-gray-400 text-sm">
                                Espacio {{ $actividad->espacio->codigo }} - {{ $actividad->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center">No hay actividad reciente.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
