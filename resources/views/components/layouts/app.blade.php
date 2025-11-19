<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Livewire Title Integration -->
    <title>{{ $title ?? 'Estacionamiento Potosí' }}</title>

    <!-- Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
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
    <!-- Layout Container -->
    <div class="flex flex-col h-screen">
        <!-- Top Navigation Bar -->
        <header class="bg-gray-800 border-b border-gray-700">
            <!-- Top Row - Logo and User Info -->
            <div class="flex items-center justify-between px-6 py-3">
                <!-- Logo Section -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 gold-gradient rounded-full flex items-center justify-center">
                        <span class="text-lg">🦙</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold gold-text font-['Playfair_Display']">Estacionamiento Potosí</h1>
                        <p class="text-xs text-gray-400">Sistema de Gestión</p>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 text-gray-400 hover:text-yellow-500 transition">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <p class="text-sm font-medium text-white">Admin User</p>
                            <p class="text-xs text-gray-400">Administrador</p>
                        </div>
                        <div class="w-8 h-8 gold-gradient rounded-full flex items-center justify-center cursor-pointer">
                            <span class="text-white font-semibold text-sm">AU</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu - Horizontal -->
            <nav class="bg-gray-750 border-t border-gray-700">
                <div class="px-6">
                    <div class="flex items-center space-x-1">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg active">
                            <i class="fas fa-tachometer-alt w-4 text-yellow-500"></i>
                            <span class="text-sm">Dashboard</span>
                        </a>

                        <!-- Espacios de Parking -->
                        <a href="{{ route('parking')}}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                            <i class="fas fa-parking w-4 text-yellow-500"></i>
                            <span class="text-sm">Parking</span>
                        </a>

                        <!-- Pagos y Facturación -->
                        <div class="dropdown relative">
                            <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                                <i class="fas fa-credit-card w-4 text-yellow-500"></i>
                                <span class="text-sm">Pagos</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-receipt mr-2 w-4"></i> Facturación
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-history mr-2 w-4"></i> Historial de Pagos
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-chart-line mr-2 w-4"></i> Reportes Financieros
                                </a>
                            </div>
                        </div>

                        <!-- Reportes -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                            <i class="fas fa-chart-bar w-4 text-yellow-500"></i>
                            <span class="text-sm">Reportes</span>
                        </a>

                        <!-- Usuarios -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg">
                            <i class="fas fa-users w-4 text-yellow-500"></i>
                            <span class="text-sm">Usuarios</span>
                        </a>
                    </div>
                </div>
            </nav>
        </header>
        <main class="flex-1 overflow-y-auto bg-gray-900">
            {{ $slot }}
        </main>
    </div>

    <!-- Animal Pattern Decorations -->
    <div class="fixed top-10 left-10 opacity-5 text-8xl text-gray-700/50 -z-10">🦙</div>
    <div class="fixed bottom-10 right-10 opacity-5 text-8xl text-gray-700/50 -z-10">🦙</div>

    <!-- Additional scripts defined via @section('scripts') -->
    @yield('scripts')
</body>
</html>
