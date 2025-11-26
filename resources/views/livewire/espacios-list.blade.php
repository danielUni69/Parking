<div>
    {{-- CDN de SweetAlert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                           class="w-full pl-9 pr-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm focus:ring-1 focus:ring-yellow-500 focus:outline-none shadow-inner text-white">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <select wire:model.live="filtroEstado" class="text-white px-2.5 py-2 bg-gray-800 border border-gray-700 rounded-lg text-xs shadow-inner">
                    <option value="todos">Todos</option>
                    <option value="libre">Libres</option>
                    <option value="ocupado">Ocupados</option>
                </select>
                <select wire:model.live="filtroTipo" class="text-white px-2.5 py-2 bg-gray-800 border border-gray-700 rounded-lg text-xs shadow-inner">
                    <option value="todos">Tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}">{{ Str::limit($tipo->nombre, 8) }}</option>
                    @endforeach
                </select>

                @auth
                    {{-- Toggle Modo Deshabilitar --}}
                    <button
                        wire:click="toggleModoEliminar"
                        class="px-3 py-2 rounded-lg text-xs flex items-center gap-1 border transition-all
                        {{ $modoEliminar ? 'bg-orange-600 text-white border-orange-500 animate-pulse' : 'bg-gray-800 text-gray-400 border-gray-700 hover:text-white' }}"
                        title="Activar modo deshabilitar"
                    >
                        <i class="fas fa-ban"></i>
                    </button>

                    <button wire:click="abrirModalCrear"
                        class="text-white px-3 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-xs flex items-center gap-1 border border-green-500 shadow-inner transition">
                            <i class="fas fa-plus"></i>
                            Nuevo
                    </button>
                @endauth
            </div>
        </div>

        {{-- Aviso visual cuando Modo Deshabilitar está activo --}}
        @if($modoEliminar)
            <div class="bg-orange-600/90 text-white text-xs text-center py-1 font-bold animate-pulse">
                <i class="fas fa-exclamation-circle"></i> MODO DESHABILITAR ACTIVO: Haz clic en un espacio para desactivarlo.
            </div>
        @endif
    </header>
    @else
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <livewire:pisos-list />
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
                        ][$tipo] ?? ['size' => 'col-span-1', 'icon' => 'fa-square', 'color' => 'from-gray-800'];

                        $borde = $modoEliminar ? 'border-orange-500 border-2 cursor-pointer' : 'border-gray-700';
                    @endphp

                    <button
                        wire:click="gestionarClickEspacio({{ $espacio->id }})"
                        class="{{ $config['size'] }} bg-gradient-to-br {{ $config['color'] }}
                            border {{ $borde }} rounded-xl p-3 flex flex-col items-center justify-center gap-1
                            transition-all hover:scale-110 shadow-lg hover:shadow-yellow-500/20 relative">

                        @if($modoEliminar || $espacio->estado === 'inactivo')
                            <div class="absolute inset-0 flex items-center justify-center bg-gray-900/60 rounded-xl z-10 backdrop-blur-[1px]">
                                <i class="fas fa-ban text-orange-500 text-2xl drop-shadow-lg"></i>
                            </div>
                        @else
                            <div class="absolute inset-0 border border-yellow-400/20 rounded-xl"></div>
                        @endif

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
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 border border-gray-700 rounded-lg w-full max-w-md">
                <div class="flex items-center justify-between p-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <i class="fas fa-magic text-purple-500"></i> Generar Nuevo Espacio
                    </h2>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="crearEspacio" class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-tag text-xs mr-1"></i> Tipo de Espacio *
                        </label>
                        <select wire:model="nuevoEspacio.tipo_espacio_id" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Seleccione un tipo</option>
                            @foreach($tipos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('nuevoEspacio.tipo_espacio_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-blue-900/20 p-3 rounded border border-blue-500/30">
                        <p class="text-sm text-blue-200 flex flex-col gap-1">
                            <span class="font-bold"><i class="fas fa-info-circle"></i> Automático:</span>
                            <span class="text-xs opacity-80 pl-6">• Código automático (Ej: P1-A01).</span>
                            <span class="text-xs opacity-80 pl-6">• Estado inicial "Libre".</span>
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="cerrarModal" class="px-4 py-2 text-gray-300 border border-gray-600 rounded hover:bg-gray-700 text-sm">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-sm shadow-lg">Generar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <livewire:ticket-crear />
    <livewire:ticket-finalizar />

    <script>
        document.addEventListener('livewire:initialized', () => {

            // Alerta de éxito simple
            @this.on('swal:success', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: 'success',
                    background: '#1f2937',
                    color: '#fff',
                    confirmButtonColor: '#10b981'
                });
            });

            // Alerta de error simple
            @this.on('swal:error', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: 'error',
                    background: '#1f2937',
                    color: '#fff',
                    confirmButtonColor: '#ef4444'
                });
            });

            // Confirmación de eliminación/deshabilitación
            @this.on('swal:confirm', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: data[0].type, // 'warning' o 'danger'
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#374151',
                    confirmButtonText: 'Sí, deshabilitar',
                    cancelButtonText: 'Cancelar',
                    background: '#1f2937',
                    color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('eliminarEspacioConfirmado', { id: data[0].id });
                    }
                });
            });
        });
    </script>
</div>
