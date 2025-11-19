<div class="min-h-screen bg-gray-950 text-gray-100 flex flex-col">
    <!-- CABECERA SUPER COMPACTA (solo 56px de alto) -->
    <header class="bg-gray-900/95 backdrop-blur border-b border-gray-800 sticky top-0 z-50">
        <div class="flex items-center justify-between px-4 py-3 text-sm gap-4">
            <div class="flex items-center gap-3 shrink-

0">
                <i class="fas fa-parking text-yellow-500 text-lg"></i>
                <livewire:pisos-list />
            </div>

            <!-- Buscador -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i class="fas fa-magnifying-glass absolute left-3 top-2.5 text-gray-500 text-xs"></i>
                    <input type="text" wire:model.live.debounce.300ms="busqueda"
                           placeholder="Código o placa..."
                           class="w-full pl-8 pr-3 py-2 bg-gray-800/70 border border-gray-700 rounded text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500">
                </div>
            </div>

            <!-- Filtros ultra pequeños -->
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
            </div>
        </div>
    </header>

    <!-- MAPA - MÁXIMA DENSIDAD (hasta 100+ espacios visibles) -->
    <main class="flex-1 p-3 overflow-auto">
        @if(!$pisoId)
            <div class="text-center py-20 text-gray-600">
                <i class="fas fa-car-side text-6xl mb-4"></i>
                <p>Selecciona un piso</p>
            </div>
        @else
            <div class="grid grid-cols-8 sm:grid-cols-10 md:grid-cols-12 lg:grid-cols-16 xl:grid-cols-20 2xl:grid-cols-24 gap-2.5">
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
                <div class="flex text-center  py-20">
                    <i class="fas fa-car-crash text-8xl text-gray-700 mb-6"></i>
                        <p class="text-2xl text-gray-500">No hay espacios con los filtros seleccionados</p>
                            </div>
            @endif
        @endif
    </main>

    <!-- Leyenda mínima -->
    <div class="fixed bottom-2 left-1/2 -translate-x-1/2 bg-gray-900/95 backdrop-blur border border-gray-800 rounded-full px-4 py-1.5 text-xs flex gap-4 shadow-xl">
        <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>Libre</span>
        <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>Ocupado</span>
    </div>

    <livewire:ticket-crear />
    <livewire:ticket-finalizar />
</div>
