<div
    x-data="{ open: false }"
    x-on:abrir-modal-crear.window="open = true; $nextTick(() => $refs.inputPlaca.focus())"
    x-on:cerrar-modal-crear.window="open = false" x-on:keydown.escape.window="open = false"
    style="display: none;" x-show="open"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

            <div class="bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-xl font-semibold leading-6 text-white flex items-center gap-2" id="modal-title">
                            <i class="fas fa-sign-in-alt text-yellow-500"></i>
                            Registrar Ingreso
                        </h3>

                        <div class="mt-4 bg-gray-700/50 p-3 rounded border border-gray-600">
                            <p class="text-sm text-gray-300 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-yellow-400"></i>
                                Espacio seleccionado:
                                <span class="text-white text-lg font-bold ml-1">{{ $espacio->codigo ?? '...' }}</span>
                            </p>
                        </div>

                        <div class="mt-6">
                            <label for="placa" class="block text-sm font-medium text-gray-300 mb-2">
                                <i class="fas fa-car-alt text-xs mr-1"></i>
                                Número de Placa *
                            </label>
                            <input type="text" wire:model="placa" x-ref="inputPlaca" wire:keydown.enter="crearTicket"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent uppercase"
                                placeholder="EJ: 2045-XTY">
                            @error('placa') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
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
        </div>
    </div>
</div>
