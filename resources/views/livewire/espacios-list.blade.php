<div>
    @if(!$enDashboard)
    <header class="bg-gray-900/95 backdrop-blur border-b border-gray-800 sticky top-0 z-50">
        <div class="flex items-center justify-between px-4 py-3 text-sm gap-4">
            <div class="flex items-center gap-3 shrink-0">
                <i class="fas fa-parking text-yellow-500 text-xl"></i>
                <livewire:pisos-list />
            </div>

            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-500 text-xs"></i>
                    <input type="text" wire:model.live.debounce.300ms="busqueda" placeholder="Código o placa..."
                           class="w-full pl-9 pr-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm focus:ring-1 focus:ring-yellow-500 focus:outline-none shadow-inner">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <select wire:model.live="filtroEstado" class="px-2.5 py-2 bg-gray-800 border border-gray-700 rounded-lg text-xs shadow-inner">
                    <option value="todos">Todos</option>
                    <option value="libre">Libres</option>
                    <option value="ocupado">Ocupados</option>
                </select>
                <select wire:model.live="filtroTipo" class="px-2.5 py-2 bg-gray-800 border border-gray-700 rounded-lg text-xs shadow-inner">
                    <option value="todos">Tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}">{{ Str::limit($tipo->nombre, 8) }}</option>
                    @endforeach
                </select>

                @auth
                    @if(Auth::user()->tipo_usuario_id == 1)
                        <button wire:click="abrirModalCrear"
                                class="px-3 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-xs flex items-center gap-1 border border-green-500 shadow-inner transition">
                            <i class="fas fa-plus"></i>
                            Nuevo
                        </button>
                    @endif
                @endauth
            </div>
        </div>
    </header>
    @else
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <livewire:pisos-list />
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-2 top-2 text-gray-400 text-xs"></i>
                <input type="text" wire:model.live.debounce.300ms="busqueda" placeholder="Buscar..."
                       class="pl-6 pr-2 py-1 bg-gray-700 border border-gray-600 rounded text-xs focus:ring-yellow-500 focus:outline-none w-32">
            </div>
            <select wire:model.live="filtroEstado" class="px-2 py-1 bg-gray-700 border border-gray-600 rounded text-xs">
                <option value="todos">Todos</option>
                <option value="libre">Libres</option>
                <option value="ocupado">Ocupados</option>
            </select>
            <select wire:model.live="filtroTipo" class="px-2 py-1 bg-gray-700 border border-gray-600 rounded text-xs">
                <option value="todos">Tipo</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ Str::limit($tipo->nombre, 6) }}</option>
                @endforeach
            </select>
            @auth
                @if(Auth::user()->tipo_usuario_id == 1)
                    <button wire:click="abrirModalCrear"
                            class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded text-xs flex items-center gap-1 transition">
                        <i class="fas fa-plus"></i>
                        <span class="hidden sm:inline">Nuevo</span>
                    </button>
                @endif
            @endauth
        </div>
    </div>
    @endif

    <div class="@if(!$enDashboard) flex-1 p-4 overflow-auto min-h-screen bg-gray-950 @endif">
        @if(!$pisoId)
            <div class="text-center py-20 text-gray-600">
                <i class="fas fa-car-side text-6xl mb-4"></i>
                Selecciona un piso
            </div>
        @else
            <div class="grid grid-cols-8 sm:grid-cols-10 md:grid-cols-12 lg:grid-cols-16 xl:grid-cols-20 2xl:grid-cols-24 gap-3 @if($enDashboard) max-h-96 overflow-y-auto @endif">
                @foreach($espacios as $espacio)
                    @php
                        $libre = $espacio->estado === 'libre';
                        $tipo = $espacio->tipoEspacio->id;
                        $config = [
                            1 => ['size' => 'col-span-1', 'icon' => 'fa-car text-lg','color' => $libre ? 'from-blue-900/40 to-blue-700/40' : 'from-gray-800 to-gray-900'],
                            2 => ['size' => 'col-span-1', 'icon' => 'fa-motorcycle text-lg','color' => $libre ? 'from-purple-900/40 to-purple-700/40' : 'from-gray-800 to-gray-900'],
                            3 => ['size' => 'col-span-2', 'icon' => 'fa-wheelchair text-xl','color' => $libre ? 'from-cyan-900/40 to-cyan-700/40' : 'from-gray-800 to-gray-900'],
                            4 => ['size' => 'col-span-2 row-span-2', 'icon' => 'fa-truck text-2xl','color' => $libre ? 'from-orange-900/40 to-orange-700/40' : 'from-gray-800 to-gray-900'],
                        ][$tipo];
                    @endphp
                    <button
                        wire:click="$dispatch('{{ $libre ? 'crearTicketParaEspacio' : 'finalizarTicketDeEspacio' }}', { espacioId: {{ $espacio->id }} })"
                        class="{{ $config['size'] }} bg-gradient-to-br {{ $config['color'] }}
                            border border-gray-700 rounded-xl p-3 flex flex-col items-center justify-center gap-1
                            transition-all hover:scale-110 shadow-lg hover:shadow-yellow-500/20 relative">
                        <div class="absolute inset-0 border border-yellow-400/20 rounded-xl"></div>
                        <i class="fas {{ $config['icon'] }} text-gray-200 drop-shadow"></i>
                        <span class="font-bold text-xs text-white tracking-wide">
                            {{ $espacio->codigo }}
                        </span>

                        @if(!$libre)
                            <span class="text-[10px] mt-1 px-2 py-0.5 rounded bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                {{ $espacio->ticketActivo->placa ?? '---' }}
                            </span>
                        @endif
                        <div class="w-2 h-2 rounded-full {{ $libre ? 'bg-emerald-400' : 'bg-red-500' }}"></div>
                    </button>
                @endforeach
            </div>
            @if($espacios->isEmpty())
                <div class="text-center py-20">
                    <i class="fas fa-car-crash text-8xl text-gray-700 mb-6"></i>
                    <p class="text-2xl text-gray-500">No hay espacios con los filtros seleccionados</p>
                </div>
            @endif
        @endif
    </div>

    @if($mostrarModal)
        <livewire:espacios-crear-modal :pisoId="$pisoId" :tipos="$tipos" />
    @endif

    @if (session()->has('message'))
        <div class="fixed top-4 right-4 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg z-50">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    @if(!$enDashboard)
    <div class="fixed bottom-2 left-1/2 -translate-x-1/2 bg-gray-900/95 backdrop-blur border border-gray-800 rounded-full px-4 py-1.5 text-xs flex gap-4 shadow-xl text-white">
        <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>Libre</span>
        <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>Ocupado</span>
    </div>
    @endif

    <livewire:ticket-crear />
    <livewire:ticket-finalizar />
</div>
