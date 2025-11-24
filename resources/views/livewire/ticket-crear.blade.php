<div>
    <div x-data="{ open: false }"
        x-on:abrir-modal-crear.window="open = true; $nextTick(() => $refs.inputPlaca.focus())"
        x-on:cerrar-modal-crear.window="open = false" x-on:keydown.escape.window="open = false"
        style="display: none;" x-show="open"
        class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">

        {{-- Contenido del Modal --}}
        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-gray-800 border border-gray-700 rounded-lg w-full max-w-md">

            {{-- Header del Modal --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-sign-in-alt text-yellow-500"></i>
                    Registrar Ingreso
                </h2>
                <button type="button" x-on:click="open = false"
                    class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form wire:submit.prevent="crearTicket" class="p-4 space-y-4">
                <div class="bg-gray-700/50 p-3 rounded border border-gray-600">
                    <p class="text-sm text-gray-300 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-yellow-400"></i>
                        <strong>Espacio seleccionado:</strong>
                        <span class="text-white text-lg font-bold ml-1">{{ $espacio->codigo ?? '...' }}</span>
                    </p>
                </div>

                <div>
                    <label for="placa" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-car-alt text-xs mr-1"></i>
                        Número de Placa *
                    </label>
                    <input type="text" wire:model="placa" x-ref="inputPlaca" wire:keydown.enter="crearTicket"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent uppercase"
                        placeholder="EJ: 2045-XTY">
                    @error('placa') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                    <button type="button" x-on:click="open = false"
                        class="px-4 py-2 text-gray-300 border border-gray-600 rounded hover:bg-gray-700 transition-colors text-sm">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors text-sm flex items-center gap-2">
                        <i class="fas fa-ticket-alt"></i>
                        Registrar Entrada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
