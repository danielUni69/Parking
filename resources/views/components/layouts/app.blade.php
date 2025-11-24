<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <title>{{ $title ?? 'Estacionamiento JEMITA' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gold-gradient {
            background: linear-gradient(135deg, #D4AF37 0%, #FFD700 50%, #FFEC8B 100%);
        }
        .gold-text {
            background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-item.active {
            background: rgba(212, 175, 55, 0.1);
            border-bottom: 3px solid #D4AF37;
        }
        .nav-item:hover {
            background: rgba(212, 175, 55, 0.05);
        }
        .bg-gray-750 {
            background-color: #374151;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 8px;
            min-width: 200px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-900 font-['Inter']">
    <div class="flex flex-col h-screen">
        <header class="bg-gray-800 border-b border-gray-700">
            <div class="flex items-center justify-between px-6 py-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gold-gradient rounded-full flex items-center justify-center">
                        <span class="text-lg">🦙</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold gold-text font-['Playfair_Display']">Estacionamiento JEMITA</h1>
                        <p class="text-xs text-gray-400">Sistema de Gestión</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="dropdown relative">
                            <div class="flex items-center space-x-3 cursor-pointer">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin User' }}</p>
                                    <p class="text-xs text-gray-400">Administrador</p>
                                </div>
                                <div class="w-8 h-8 gold-gradient rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name ?? 'AU', 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="dropdown-menu" style="right: 0; left: auto;">
                                <div class="px-4 py-2 border-b border-gray-600">
                                    <p class="text-sm text-white font-medium">{{ Auth::user()->name ?? 'Admin User' }}</p>
                                    <p class="text-xs text-gray-400">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-user-cog mr-2 w-4"></i> Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-red-400 hover:bg-red-900 hover:text-white transition">
                                        <i class="fas fa-sign-out-alt mr-2 w-4"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
            @auth
           <nav class="bg-gray-750 border-t border-gray-700">
                <div class="px-6">
                    <div class="flex items-center space-x-1">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg ">
                            <i class="fas fa-tachometer-alt w-4 text-yellow-500"></i>
                            <span class="text-sm">Dashboard</span>
                        </a>

                        <!-- Espacios de Parking -->
                        <a href="{{ route('parking')}}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                            <i class="fas fa-parking w-4 text-yellow-500"></i>
                            <span class="text-sm">Parking</span>
                        </a>

                        <!-- Pagos y Facturación -->

                        <!-- Reportes -->
                        <a href="{{ route('reportes.pagos')}}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                            <i class="fas fa-chart-bar w-4 text-yellow-500"></i>
                            <span class="text-sm">Reportes</span>
                        </a>

                        <!-- Usuarios -->
                        <a href="{{ route('users') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                            <i class="fas fa-users w-4 text-yellow-500"></i>
                            <span class="text-sm">Usuarios</span>
                        </a>
                    </div>
                </nav>
            @endauth
        </header>
        <main class="flex-1 overflow-y-auto bg-gray-900">
            {{ $slot }}
        </main>
    </div>
    <div class="fixed top-10 left-10 opacity-5 text-8xl text-gray-700/50 -z-10">🦙</div>
    <div class="fixed bottom-10 right-10 opacity-5 text-8xl text-gray-700/50 -z-10">🦙</div>
    @yield('scripts')
</body>
</html>
