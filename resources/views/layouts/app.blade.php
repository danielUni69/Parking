<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Estacionamiento Potosí')</title>
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
<body class="bg-gray-900 font-sans">
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
                        <button class="p-2 text-gray-400 hover:text-yellow-500 transition" id="notifications-btn">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full" id="notification-badge"></span>
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <p class="text-sm font-medium text-white" id="user-name">Admin User</p>
                            <p class="text-xs text-gray-400" id="user-role">Administrador</p>
                        </div>
                        <div class="w-8 h-8 gold-gradient rounded-full flex items-center justify-center cursor-pointer" id="user-profile">
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
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg dashboard-link">
                            <i class="fas fa-tachometer-alt w-4 text-yellow-500"></i>
                            <span class="text-sm">Dashboard</span>
                        </a>

                        <!-- Gestión de Vehículos -->
                        <div class="dropdown relative">
                            <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg vehiculos-link">
                                <i class="fas fa-car w-4 text-yellow-500"></i>
                                <span class="text-sm">Vehículos</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-list mr-2 w-4"></i>
                                    Lista de Vehículos
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-plus mr-2 w-4"></i>
                                    Nuevo Ingreso
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i>
                                    Registrar Salida
                                </a>
                            </div>
                        </div>

                        <!-- Espacios de Parking -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg espacios-link">
                            <i class="fas fa-parking w-4 text-yellow-500"></i>
                            <span class="text-sm">Parking</span>
                        </a>

                        <!-- Reservas -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg reservas-link">
                            <i class="fas fa-calendar-check w-4 text-yellow-500"></i>
                            <span class="text-sm">Reservas</span>
                        </a>

                        <!-- Pagos y Facturación -->
                        <div class="dropdown relative">
                            <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg pagos-link">
                                <i class="fas fa-credit-card w-4 text-yellow-500"></i>
                                <span class="text-sm">Pagos</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-receipt mr-2 w-4"></i>
                                    Facturación
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-history mr-2 w-4"></i>
                                    Historial de Pagos
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-200 hover:bg-gray-700 hover:text-white transition">
                                    <i class="fas fa-chart-line mr-2 w-4"></i>
                                    Reportes Financieros
                                </a>
                            </div>
                        </div>

                        <!-- Reportes -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg reportes-link">
                            <i class="fas fa-chart-bar w-4 text-yellow-500"></i>
                            <span class="text-sm">Reportes</span>
                        </a>

                        <!-- Usuarios -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg usuarios-link">
                            <i class="fas fa-users w-4 text-yellow-500"></i>
                            <span class="text-sm">Usuarios</span>
                        </a>

                        <!-- Configuración -->
                        <a href="#" class="nav-item flex items-center space-x-2 px-4 py-3 text-gray-200 rounded-t-lg configuracion-link">
                            <i class="fas fa-cog w-4 text-yellow-500"></i>
                            <span class="text-sm">Configuración</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Page Header -->
            <div class="bg-gray-800 border-t border-gray-700">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Page Title and Breadcrumbs -->
                    <div class="flex items-center space-x-4">
                        <h2 class="text-xl font-bold text-white" id="page-title">@yield('page-title', 'Dashboard')</h2>
                        <div class="flex items-center space-x-2 text-sm text-gray-400" id="breadcrumbs-container">
                            @hasSection('breadcrumbs')
                                @yield('breadcrumbs')
                            @else
                                <span class="text-yellow-500"><i class="fas fa-home"></i></span>
                                <span><i class="fas fa-chevron-right text-xs"></i></span>
                                <span>Inicio</span>
                            @endif
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-md mx-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input
                                type="text"
                                placeholder="Buscar vehículo, reserva o usuario..."
                                class="w-full pl-10 pr-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                id="global-search"
                            >
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex items-center space-x-2" id="quick-actions">
                        <button class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Nuevo Ingreso
                        </button>
                        <button class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Registrar Salida
                        </button>
                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                            <i class="fas fa-print mr-1"></i>
                            Reporte
                        </button>
                    </div>
                </div>

                <!-- Page Actions -->
                <div class="px-6 py-3 bg-gray-750 border-t border-gray-700" id="page-actions-container">
                    <div class="flex items-center justify-center">
                        <div class="flex items-center space-x-2" id="page-actions-left">
                            @hasSection('page-actions')
                                @yield('page-actions')
                            @else
                                <livewire:pisos-list />
                            @endif
                        </div>
                        <div class="flex items-center space-x-2" id="page-actions-right">
                            @hasSection('page-actions-right')
                                @yield('page-actions-right')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-900 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Animal Pattern Decorations -->
    <div class="fixed top-10 left-10 opacity-5 text-8xl">🦙</div>
    <div class="fixed bottom-10 right-10 opacity-5 text-8xl">🦙</div>

    <script>
        // Navigation Management
        class NavigationManager {
            constructor() {
                this.currentSection = 'dashboard';
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.loadSection(this.currentSection);
                this.setActiveNavItem('dashboard');
            }

            setupEventListeners() {
                // Main navigation
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        const section = this.getSectionFromElement(item);
                        this.navigateTo(section);
                    });
                });

                // Search functionality
                document.getElementById('global-search').addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        this.handleSearch(e.target.value);
                    }
                });

                // Quick actions
                document.querySelectorAll('#quick-actions button').forEach(button => {
                    button.addEventListener('click', (e) => {
                        this.handleQuickAction(button.textContent.trim());
                    });
                });
            }

            getSectionFromElement(element) {
                if (element.classList.contains('dashboard-link')) return 'dashboard';
                if (element.classList.contains('vehiculos-link')) return 'vehiculos';
                if (element.classList.contains('espacios-link')) return 'espacios';
                if (element.classList.contains('reservas-link')) return 'reservas';
                if (element.classList.contains('pagos-link')) return 'pagos';
                if (element.classList.contains('reportes-link')) return 'reportes';
                if (element.classList.contains('usuarios-link')) return 'usuarios';
                if (element.classList.contains('configuracion-link')) return 'configuracion';
                return 'dashboard';
            }

            setActiveNavItem(section) {
                // Remove active class from all items
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Add active class to current section
                const activeLink = document.querySelector(`.${section}-link`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }

            navigateTo(section) {
                this.currentSection = section;
                this.setActiveNavItem(section);
                this.loadSection(section);
            }

            loadSection(section) {
                // Update page title and breadcrumbs
                const titles = {
                    'dashboard': 'Dashboard Principal',
                    'vehiculos': 'Gestión de Vehículos',
                    'espacios': 'Espacios de Parking',
                    'reservas': 'Sistema de Reservas',
                    'pagos': 'Pagos y Facturación',
                    'reportes': 'Reportes y Estadísticas',
                    'usuarios': 'Gestión de Usuarios',
                    'configuracion': 'Configuración del Sistema'
                };

                document.getElementById('page-title').textContent = titles[section] || 'Dashboard';
                this.updateBreadcrumbs(section);

                // Simulate loading content
                console.log(`Cargando sección: ${section}`);
                this.showSectionContent(section);
            }

            updateBreadcrumbs(section) {
                const breadcrumbs = {
                    'dashboard': ['Inicio'],
                    'vehiculos': ['Inicio', 'Vehículos'],
                    'espacios': ['Inicio', 'Parking'],
                    'reservas': ['Inicio', 'Reservas'],
                    'pagos': ['Inicio', 'Pagos'],
                    'reportes': ['Inicio', 'Reportes'],
                    'usuarios': ['Inicio', 'Usuarios'],
                    'configuracion': ['Inicio', 'Configuración']
                };

                const container = document.getElementById('breadcrumbs-container');
                const crumbs = breadcrumbs[section] || ['Inicio'];

                container.innerHTML = crumbs.map((crumb, index) =>
                    index === 0
                        ? `<span class="text-yellow-500"><i class="fas fa-home"></i> ${crumb}</span>`
                        : `<span><i class="fas fa-chevron-right text-xs mx-2"></i></span><span>${crumb}</span>`
                ).join('');
            }

            showSectionContent(section) {
                const contentMap = {
                    'dashboard': this.getDashboardContent(),
                    'vehiculos': this.getVehiculosContent(),
                    'espacios': this.getEspaciosContent(),
                    'reservas': this.getReservasContent(),
                    'pagos': this.getPagosContent(),
                    'reportes': this.getReportesContent(),
                    'usuarios': this.getUsuariosContent(),
                    'configuracion': this.getConfiguracionContent()
                };

                const mainContent = document.querySelector('main');
                mainContent.innerHTML = contentMap[section] || this.getDefaultContent(section);
            }

            getDashboardContent() {
                return `
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-sm">Espacios Totales</p>
                                    <p class="text-3xl font-bold text-white mt-2">120</p>
                                </div>
                                <div class="w-12 h-12 gold-gradient rounded-full flex items-center justify-center">
                                    <i class="fas fa-parking text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-sm">Disponibles</p>
                                    <p class="text-3xl font-bold text-white mt-2">42</p>
                                </div>
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-car text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-sm">Ocupados</p>
                                    <p class="text-3xl font-bold text-white mt-2">68</p>
                                </div>
                                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-times text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-400 text-sm">Ingresos Hoy</p>
                                    <p class="text-3xl font-bold text-white mt-2">Bs. 1,240</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Actividad Reciente</h3>
                        <p class="text-gray-400">Bienvenido al Dashboard del Estacionamiento Potosí</p>
                    </div>
                `;
            }

            getVehiculosContent() {
                return `
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Gestión de Vehículos</h3>
                        <p class="text-gray-400">Módulo de gestión de vehículos - En desarrollo</p>
                    </div>
                `;
            }

            getEspaciosContent() {
                return `
                    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                        <h3 class="text-xl font-bold text-white mb-4">Espacios de Parking</h3>
                        <p class="text-gray-400">Módulo de gestión de espacios - En desarrollo</p>
                    </div>
                `;
            }

            getDefaultContent(section) {
                const sectionName = section.charAt(0).toUpperCase() + section.slice(1);
                return `
                    <div class="text-center py-8">
                        <i class="fas fa-cog text-6xl text-yellow-500 mb-4"></i>
                        <h3 class="text-2xl text-white">${sectionName}</h3>
                        <p class="text-gray-400 mt-2">Módulo en desarrollo</p>
                    </div>
                `;
            }

            // Add other content methods similarly...

            handleSearch(query) {
                if (query.trim()) {
                    alert(`Buscando: ${query}`);
                    // Implement search logic here
                }
            }

            handleQuickAction(action) {
                alert(`Acción rápida: ${action}`);
            }
        }

        // Initialize navigation manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            window.navManager = new NavigationManager();
        });

        // Utility functions
        function refreshData() {
            alert('Actualizando datos...');
        }

        function exportData() {
            alert('Exportando datos...');
        }
    </script>

    @yield('scripts')
</body>
</html>
