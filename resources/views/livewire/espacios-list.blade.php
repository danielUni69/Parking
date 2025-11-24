<div>
    @if(!$enDashboard)
    <header class="bg-gray-900/95 backdrop-blur border-b border-gray-800 sticky top-0 z-50">
        <div class="flex items-center justify-between px-4 py-3 text-sm gap-4">
            <div class="flex items-center gap-3 shrink-0">
                <i class="fas fa-parking text-yellow-500 text-lg"></i>
                <livewire:pisos-list />
            </div>
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i class="fas fa-magnifying-glass absolute left-3 top-2.5 text-gray-500 text-xs"></i>
                    <input type="text" wire:model.live.debounce.300ms="busqueda"
                           placeholder="Código o placa..."
                           class="w-full pl-8 pr-3 py-2 bg-gray-800/70 border border-gray-700 rounded text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <select wire:model.live="filtroEstado" class="px-2.5 py-2 bg-gray-800/70 border border-gray-700 rounded text-xs">
                    <option value="todos">Todos</option>
                    <option value="libre">Libres</option>
                    <option value="ocupado">Ocupados</option>
                </select>
                <select wire:model.live="filtroTipo" class="px-2.5 py-2 bg-gray-800/70 border border-gray-700 rounded text-xs">
                    <option value="todos">Tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}">{{ Str::limit($tipo->nombre, 8) }}</option>
                    @endforeach
                </select>
                @auth
                    @if(Auth::user()->tipo_usuario_id == 1)
                        <button
                            wire:click="abrirModalCrear"
                            class="px-2.5 py-2 bg-green-600 hover:bg-green-700 border border-green-500 rounded text-xs flex items-center gap-1 transition-colors"
                            title="Crear nuevo espacio"
                        >
                            <i class="fas fa-plus text-xs"></i>
                            <span>Nuevo</span>
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
                <i class="fas fa-magnifying-glass absolute left-2 top-2 text-gray-400 text-xs"></i>
                <input type="text" wire:model.live.debounce.300ms="busqueda"
                       placeholder="Buscar..."
                       class="pl-6 pr-2 py-1 bg-gray-700 border border-gray-600 rounded text-xs focus:outline-none focus:ring-1 focus:ring-yellow-500 w-32">
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
                    <button
                        wire:click="abrirModalCrear"
                        class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded text-xs flex items-center gap-1 transition-colors"
                        title="Crear nuevo espacio"
                    >
                        <i class="fas fa-plus text-xs"></i>
                        <span class="hidden sm:inline">Nuevo</span>
                    </button>
                @endif
            @endauth
        </div>
    </div>
    @endif
    <div class="@if(!$enDashboard) flex-1 p-3 overflow-auto min-h-screen bg-gray-950 @endif">
        @if(!$pisoId)
            <div class="text-center py-20 text-gray-600">
                <i class="fas fa-car-side text-6xl mb-4"></i>
                <p>Selecciona un piso</p>
            </div>
        @else
            <div class="grid grid-cols-8 sm:grid-cols-10 md:grid-cols-12 lg:grid-cols-16 xl:grid-cols-20 2xl:grid-cols-24 gap-2.5 @if($enDashboard) max-h-96 overflow-y-auto @endif">
                @foreach($espacios as $espacio)
                    @php
                        $libre = $espacio->estado === 'libre';
                        $tipo = $espacio->tipoEspacio->id;
                        $config = [
                            1 => ['size' => 'col-span-1 row-span-1', 'icon' => 'fa-car text-lg',         'color' => $libre ? 'bg-blue-900/50 border-blue-500/70 hover:bg-blue-900/70' : 'bg-gray-900/60 border-gray-700'],
                            2 => ['size' => 'col-span-1 row-span-1', 'icon' => 'fa-motorcycle text-base', 'color' => $libre ? 'bg-purple-900/50 border-purple-500/70 hover:bg-purple-900/70' : 'bg-gray-900/60 border-gray-700'],
                            3 => ['size' => 'col-span-2 row-span-1', 'icon' => 'fa-wheelchair text-xl',   'color' => $libre ? 'bg-cyan-900/50 border-cyan-400 hover:bg-cyan-900/70' : 'bg-gray-900/60 border-gray-700'],
                            4 => ['size' => 'col-span-2 row-span-2', 'icon' => 'fa-truck text-2xl',       'color' => $libre ? 'bg-orange-900/50 border-orange-500/70 hover:bg-orange-900/70' : 'bg-gray-900/60 border-gray-700'],
                        ][$tipo] ?? $config[1];
                    @endphp
                    @auth
                    <button
                        wire:click="$dispatch('{{ $libre ? 'crearTicketParaEspacio' : 'finalizarTicketDeEspacio' }}', { espacioId: {{ $espacio->id }} })"
                        class="{{ $config['size'] }} {{ $config['color'] }} border rounded-lg p-2 flex flex-col items-center justify-center gap-1 transition-all hover:scale-110 hover:shadow-lg hover:shadow-yellow-500/30 group cursor-pointer">
                        <i class="fas {{ $config['icon'] }} text-gray-300 group-hover:text-white"></i>
                        <span class="font-bold text-xs">{{ $espacio->codigo }}</span>
                        <div class="w-2 h-2 rounded-full {{ $libre ? 'bg-emerald-400' : 'bg-red-500' }} shadow"></div>
                    </button>
                    @endauth
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
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 border border-gray-700 rounded-lg w-full max-w-md">
                <div class="flex items-center justify-between p-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <i class="fas fa-plus-circle text-green-500"></i>
                        Crear Nuevo Espacio
                    </h2>
                    <button
                        wire:click="cerrarModal"
                        class="text-gray-400 hover:text-white transition-colors"
                    >
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <form wire:submit.prevent="crearEspacio" class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-hashtag text-xs mr-1"></i>
                            Código del Espacio *
                        </label>
                        <input
                            type="text"
                            wire:model="nuevoEspacio.codigo"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Ej: A-001, B-015, etc."
                            autofocus
                        >
                        @error('nuevoEspacio.codigo')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-tag text-xs mr-1"></i>
                            Tipo de Espacio *
                        </label>
                        <select
                            wire:model="nuevoEspacio.tipo_espacio_id"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                            <option value="">Seleccione un tipo</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('nuevoEspacio.tipo_espacio_id')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-circle text-xs mr-1"></i>
                            Estado *
                        </label>
                        <select
                            wire:model="nuevoEspacio.estado"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                            <option value="libre">🟢 Libre</option>
                            <option value="ocupado">🔴 Ocupado</option>
                        </select>
                        @error('nuevoEspacio.estado')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="bg-gray-700/50 p-3 rounded border border-gray-600">
                        <p class="text-sm text-gray-300 flex items-center gap-2">
                            <i class="fas fa-layer-group text-blue-400"></i>
                            <strong>Piso:</strong>
                            @php
                                $piso = \App\Models\Piso::find($pisoId);
                            @endphp
                            <span class="text-white">{{ $piso->numero ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                        <button
                            type="button"
                            wire:click="cerrarModal"
                            class="px-4 py-2 text-gray-300 border border-gray-600 rounded hover:bg-gray-700 transition-colors text-sm"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors text-sm flex items-center gap-2"
                        >
                            <i class="fas fa-plus"></i>
                            Crear Espacio
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
    <div class="fixed bottom-2 left-1/2 -translate-x-1/2 bg-gray-900/95 backdrop-blur border border-gray-800 rounded-full px-4 py-1.5 text-xs flex gap-4 shadow-xl">
        <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>Libre</span>
        <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>Ocupado</span>
    </div>
    @endif
    <livewire:ticket-crear />
    <livewire:ticket-finalizar />
</div>
