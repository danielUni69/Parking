<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <title>{{ $title ?? 'Estacionamiento JEMITA' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
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
            background: rgba(212, 175, 55, 0.15);
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
<body class="bg-gray-900">
    <div class="flex flex-col h-screen">
        <header class="bg-gray-800 border-b border-gray-700">
            <div class="flex items-center justify-between px-6 py-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo JEMITA" class="h-full w-auto">
                    </div>
                    <div>
                        <h1 class="text-lg font-medium gold-text">Estacionamiento JEMITA</h1>
                        <p class="text-xs text-gray-400">Sistema de Gestión</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="dropdown relative">
                            <div class="flex items-center space-x-3 cursor-pointer">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-white flex items-center space-x-2">
                                        <span>{{ Auth::user()->name ?? 'Admin User' }}</span>
                                        <svg class="w-5 h-5 {{ Auth::user()->tipo_usuario_id == 1 ? 'text-yellow-400' : 'text-blue-400' }}" 
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd"/>
                                        </svg>
                                    </p>
                                    <p class="text-xs text-gray-400">{{ ucfirst(Auth::user()->tipo->tipo ?? 'desconocido') }}</p>
                                </div>
                                <div class="w-8 h-8 gold-gradient rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name ?? 'AU', 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="dropdown-menu right-0">
                                <div class="px-4 py-2 border-b border-gray-600">
                                    <p class="text-sm text-white font-medium">{{ Auth::user()->name ?? 'Admin User' }}</p>
                                    <p class="text-xs text-gray-400">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                                    <p class="text-xs text-gray-300 mt-1">
                                        Tipo: <span class="font-medium">{{ ucfirst(Auth::user()->tipo->tipo ?? 'desconocido') }}</span>
                                    </p>
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
                    <div class="px-6 flex justify-center">
                        <div class="flex space-x-1">
                            <a href="{{ route('dashboard') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt w-4 text-yellow-500"></i>
                                <span class="text-sm">Dashboard</span>
                            </a>
                            <a href="{{ route('parking') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('parking') ? 'active' : '' }}">
                                <i class="fas fa-parking w-4 text-yellow-500"></i>
                                <span class="text-sm">Parking</span>
                            </a>
                            <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('reportes') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar w-4 text-yellow-500"></i>
                                <span class="text-sm">Reportes</span>
                            </a>
                            @if(Auth::user()->tipo_usuario_id == 1)
                                <a href="{{ route('users') }}" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg {{ request()->routeIs('users') ? 'active' : '' }}">
                                    <i class="fas fa-users w-4 text-yellow-500"></i>
                                    <span class="text-sm">Usuarios</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </nav>
            @endauth
        </header>
        <main class="flex-1 overflow-y-auto bg-gray-900 p-6">
            {{ $slot }}
        </main>
    </div>
    <a href="{{ route('parking') }}" class="fixed bottom-6 right-6 w-16 h-16 gold-gradient rounded-full flex items-center justify-center shadow-xl hover:scale-110 transition-transform z-50">
        <img src="{{ asset('img/logoN.png') }}" alt="Ir a Parking" class="w-10 h-10">
    </a>

    @yield('scripts')
</body>
</html>
