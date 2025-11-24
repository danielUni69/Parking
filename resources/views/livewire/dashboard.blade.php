<div>
    <div class="m-4">
        <div class="mb-8">
            <div class="text-center mb-6">
                <h2 class="text-4xl font-bold text-white mb-2">Nuestras Tarifas</h2>
                <p class="text-gray-400 text-lg">Espacios disponibles y precios por hora</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($tiposEspacios as $tipo)
                    @php
                        $disponibles = $espaciosPorTipo[$tipo->id] ?? 0;
                        $currentHour = now()->hour;
                        $isNightRate = $currentHour >= 18 || $currentHour < 6;
                        $tarifaBase = $tipo->tarifa_hora;
                        $tarifaActual = $isNightRate ? $tarifaBase + 2 : $tarifaBase;
                    @endphp

                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 border border-gray-700 hover:border-yellow-500 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105">
                        <!-- Icono según tipo -->
                        <div class="flex justify-center mb-4">
                            @if($tipo->nombre === 'Auto normal')
                                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-car text-white text-3xl"></i>
                                </div>
                            @elseif($tipo->nombre === 'Moto')
                                <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-motorcycle text-white text-3xl"></i>
                                </div>
                            @elseif($tipo->nombre === 'Discapacitado')
                                <div class="w-20 h-20 bg-purple-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-wheelchair text-white text-3xl"></i>
                                </div>
                            @else
                                <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-truck text-white text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Nombre del tipo -->
                        <h3 class="text-2xl font-bold text-white text-center mb-2">{{ $tipo->nombre }}</h3>
                        <p class="text-gray-400 text-sm text-center mb-4">{{ $tipo->descripcion }}</p>

                        <!-- Tarifa -->
                        <div class="bg-gray-700 rounded-xl p-4 mb-4">
                            <div class="text-center">
                                <p class="text-gray-400 text-sm mb-1">Tarifa por hora</p>
                                <p class="text-4xl font-bold text-yellow-400">Bs. {{ number_format($tarifaActual, 2) }}</p>
                                @if($isNightRate)
                                    <div class="mt-2 inline-flex items-center bg-blue-900 text-blue-200 px-3 py-1 rounded-full text-xs">
                                        <i class="fas fa-moon mr-1"></i>
                                        <span>Tarifa nocturna (18:00 - 6:00)</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Disponibilidad -->
                        <div class="text-center">
                            @if($disponibles > 0)
                                <div class="inline-flex items-center bg-green-900 text-green-300 px-4 py-2 rounded-full">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span class="font-semibold">{{ $disponibles }} {{ $disponibles == 1 ? 'espacio disponible' : 'espacios disponibles' }}</span>
                                </div>
                            @else
                                <div class="inline-flex items-center bg-red-900 text-red-300 px-4 py-2 rounded-full">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    <span class="font-semibold">No disponible</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Nota de tarifa nocturna -->
        <div class="mb-8 text-center">
            <div class="inline-block bg-gradient-to-r from-blue-900 to-indigo-900 rounded-xl p-4 border border-blue-700">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                <span class="text-blue-200">Se aplica un cargo adicional de Bs. 2.00 en horario nocturno (18:00 - 6:00)</span>
            </div>
        </div>

        @auth
            <!-- Dashboard Stats para usuarios autenticados -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-6">Panel de Control</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
        @endauth

        <!-- Mapa y Actividad -->
        <div class="grid grid-cols-1 @auth lg:grid-cols-3 @endauth gap-6">
            <div class="@auth lg:col-span-2 @endauth bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Nuestra Ubicación</h2>
                    @auth
                        <button id="reloadLocation" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-sync-alt mr-2"></i> Recargar Ubicación
                        </button>
                    @endauth
                </div>
                <div id="map" style="height: 500px; width: 100%; border-radius: 0.5rem;"></div>
            </div>

            @auth
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
            @endauth
        </div>
    </div>

    <style>
        #map {
            filter: drop-shadow(0 0 0.5rem rgba(0, 0, 0, 0.3));
            min-height: 500px;
        }
        .leaflet-container {
            background: #2d3748;
        }
        .leaflet-control-attribution {
            background: rgba(45, 55, 72, 0.8) !important;
            color: white !important;
        }
        .leaflet-routing-container {
            background: rgba(45, 55, 72, 0.9) !important;
            color: white !important;
        }
        .gold-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
    </style>

    <script>
            document.addEventListener('DOMContentLoaded', function() {
                // VERIFICACIÓN DE SEGURIDAD:
                // Si no existe el div del mapa, detenemos el script para evitar errores.
                if (!document.getElementById('map')) return;

                const staticPoint = [-19.58794772850716, -65.75752515850782];

                const map = L.map('map', {
                    zoomControl: true,
                    attributionControl: false
                }).setView(staticPoint, 15);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                const staticIcon = L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });

                L.marker(staticPoint, {icon: staticIcon}).addTo(map)
                    .bindPopup('Punto de Estacionamiento')
                    .openPopup();

                const userIcon = L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/484/484167.png',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });

                let routingControl = null;

                // Definición de la función
                function loadUserLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) { // Callback de éxito
                                const userLat = position.coords.latitude;
                                const userLng = position.coords.longitude;
                                const userLocation = [userLat, userLng];

                                // Limpiar marcadores antiguos (excepto el estático)
                                map.eachLayer(function(layer) {
                                    if (layer instanceof L.Marker && layer.getLatLng().lat !== staticPoint[0]) {
                                        map.removeLayer(layer);
                                    }
                                });

                                // Limpiar ruta anterior si existe
                                if (routingControl !== null) {
                                    map.removeControl(routingControl);
                                    routingControl = null; // Resetear variable
                                }

                                // Marcador del usuario
                                L.marker(userLocation, {icon: userIcon}).addTo(map)
                                    .bindPopup('Tu ubicación actual')
                                    .openPopup();

                                // Trazar ruta
                                if (typeof L.Routing !== 'undefined') {
                                    routingControl = L.Routing.control({
                                        waypoints: [
                                            L.latLng(userLocation),
                                            L.latLng(staticPoint)
                                        ],
                                        routeWhileDragging: true,
                                        lineOptions: {
                                            styles: [{color: '#3b82f6', weight: 4}]
                                        },
                                        createMarker: function() { return null; }, // No crear marcadores extra
                                        addWaypoints: false
                                    }).addTo(map);
                                } else {
                                    console.warn('Librería Leaflet Routing Machine no cargada.');
                                }
                            },
                            function(error) { // Callback de error
                                alert("No se pudo obtener tu ubicación: " + error.message);
                            },
                            { // Opciones
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        alert("Geolocalización no soportada por este navegador.");
                    }
                } // Cierre de loadUserLocation

                // Ejecutar al inicio
                loadUserLocation();

                // Event Listener condicional (Solo si el usuario está logueado)
                @auth
                    const reloadBtn = document.getElementById('reloadLocation');
                    if (reloadBtn) {
                        reloadBtn.addEventListener('click', loadUserLocation);
                    }
                @endauth

            }); // <--- ¡IMPORTANTE! Este cierre faltaba o estaba mal ubicado en tu archivo original.
        </script>
</div>
