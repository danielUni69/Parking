<div class="m-4">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Bienvenido al Sistema</h1>
        <p class="text-gray-400 mt-2">Gestión completa del estacionamiento Jemita - {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Spaces -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Espacios Totales</p>
                    <p class="text-3xl font-bold text-white mt-2">120</p>
                </div>
                <div class="w-12 h-12 gold-gradient rounded-full flex items-center justify-center">
                    <i class="fas fa-parking text-white"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-green-400 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>+5% vs ayer</span>
                </div>
            </div>
        </div>

        <!-- Available Spaces -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-green-500 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Disponibles</p>
                    <p class="text-3xl font-bold text-white mt-2">42</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-car text-white"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-green-400 text-sm">
                    <i class="fas fa-check mr-1"></i>
                    <span>35% disponibles</span>
                </div>
            </div>
        </div>

        <!-- Occupied Spaces -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-red-500 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Ocupados</p>
                    <p class="text-3xl font-bold text-white mt-2">68</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-white"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-red-400 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>57% ocupación</span>
                </div>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-blue-500 transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Ingresos Hoy</p>
                    <p class="text-3xl font-bold text-white mt-2">Bs. 1,240</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-green-400 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>+12% vs ayer</span>
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

            <!-- Parking Visualization -->
            <div class="bg-gray-700 rounded-lg p-6">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-white">Planta Baja - Zona A</h3>
                    <p class="text-gray-400">Estado actual de los espacios</p>
                </div>

                <!-- Legend -->
                <div class="flex justify-center space-x-6 mb-6">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="text-gray-300 text-sm">Disponible</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span class="text-gray-300 text-sm">Ocupado</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                        <span class="text-gray-300 text-sm">Reservado</span>
                    </div>
                </div>

                <!-- Parking Grid -->
                <div class="grid grid-cols-5 gap-4">
                    <!-- Generate parking spots -->
                    <div class="parking-spot bg-green-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">A1</div>
                    <div class="parking-spot bg-red-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">A2</div>
                    <div class="parking-spot bg-green-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">A3</div>
                    <div class="parking-spot bg-yellow-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">A4</div>
                    <div class="parking-spot bg-green-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">A5</div>

                    <div class="parking-spot bg-red-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">B1</div>
                    <div class="parking-spot bg-green-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">B2</div>
                    <div class="parking-spot bg-green-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">B3</div>
                    <div class="parking-spot bg-red-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">B4</div>
                    <div class="parking-spot bg-green-500 text-white p-4 rounded text-center font-semibold cursor-pointer hover:opacity-80 transition">B5</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <h2 class="text-xl font-bold text-white mb-6">Actividad Reciente</h2>
            <div class="space-y-4">
                <!-- Activity Items -->
                <div class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-car text-white"></i>
                    </div>
                    <div>
                        <p class="text-white font-medium">Vehículo ingresó</p>
                        <p class="text-gray-400 text-sm">Espacio B3 - Hace 5 min</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-credit-card text-white"></i>
                    </div>
                    <div>
                        <p class="text-white font-medium">Pago registrado</p>
                        <p class="text-gray-400 text-sm">Bs. 15 - Hace 12 min</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                    <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white"></i>
                    </div>
                    <div>
                        <p class="text-white font-medium">Reserva realizada</p>
                        <p class="text-gray-400 text-sm">Espacio A4 - Hace 25 min</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-sign-out-alt text-white"></i>
                    </div>
                    <div>
                        <p class="text-white font-medium">Vehículo salió</p>
                        <p class="text-gray-400 text-sm">Espacio C2 - Hace 38 min</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-white mb-4">Acciones Rápidas</h3>
                <div class="grid grid-cols-2 gap-3">
                    <button class="p-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-plus mb-1"></i>
                        <p class="text-xs">Nuevo Ingreso</p>
                    </button>
                    <button class="p-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-sign-out-alt mb-1"></i>
                        <p class="text-xs">Registrar Salida</p>
                    </button>
                    <button class="p-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-search mb-1"></i>
                        <p class="text-xs">Buscar Vehículo</p>
                    </button>
                    <button class="p-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-print mb-1"></i>
                        <p class="text-xs">Generar Reporte</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
