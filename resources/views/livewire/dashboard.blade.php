<div>
    <div class="m-4">
        <div class="mb-8">
            @if($espaciosDisponibles > 0)
                <div class="bg-green-800 rounded-xl p-6 border border-green-700 flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-green-100 font-bold">¡Espacios disponibles!</p>
                        <p class="text-green-200 text-sm">Hay {{ $espaciosDisponibles }} espacios libres.</p>
                    </div>
                </div>
            @else
                <div class="bg-red-800 rounded-xl p-6 border border-red-700 flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-red-100 font-bold">¡No hay espacios! :(</p>
                        <p class="text-red-200 text-sm">Todos los espacios están ocupados.</p>
                    </div>
                </div>
            @endif
        </div>

        @auth
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Bienvenido al Sistema</h1>
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
        <div class="grid grid-cols-1 @auth lg:grid-cols-3 @endauth gap-6">
            <div class="@auth lg:col-span-2 @endauth bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Mapa de Ubicación</h2>
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            function loadUserLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;
                            const userLocation = [userLat, userLng];
                            map.eachLayer(function(layer) {
                                if (layer instanceof L.Marker && layer.getLatLng().lat !== staticPoint[0]) {
                                    map.removeLayer(layer);
                                }
                            });
                            if (routingControl !== null) {
                                map.removeControl(routingControl);
                            }
                            L.marker(userLocation, {icon: userIcon}).addTo(map)
                                .bindPopup('Tu ubicación actual')
                                .openPopup();
                            routingControl = L.Routing.control({
                                waypoints: [
                                    L.latLng(userLocation),
                                    L.latLng(staticPoint)
                                ],
                                routeWhileDragging: true,
                                lineOptions: {
                                    styles: [{color: '#3b82f6', weight: 4}]
                                },
                                createMarker: function() { return null; }
                            }).addTo(map);
                        },
                        function(error) {
                            alert("No se pudo obtener tu ubicación: " + error.message);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } else {
                    alert("Geolocalización no soportada por este navegador.");
                }
            }
            loadUserLocation();
            @auth
                document.getElementById('reloadLocation').addEventListener('click', loadUserLocation);
            @endauth
        });
    </script>
</div>
