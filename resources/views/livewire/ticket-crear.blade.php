<div>
    {{-- Usamos x-data para controlar el estado local del modal --}}
    <div
        x-data="{ open: false }"
        x-on:abrir-modal-crear.window="open = true; $nextTick(() => $refs.inputPlaca.focus())"
        x-on:cerrar-modal-crear.window="open = false"
        x-on:keydown.escape.window="open = false"
        style="display: none;"
        x-show="open"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">

        {{-- Backdrop oscuro --}}
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

            {{-- Contenido del Modal --}}
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">
                                Registrar Ingreso
                            </h3>

                            <div class="mt-4 bg-blue-50 p-3 rounded-md border border-blue-100">
                                <p class="text-sm text-blue-700">
                                    Espacio seleccionado: <span class="font-bold text-lg ml-1">{{ $espacio->codigo ?? '...' }}</span>
                                </p>
                            </div>

                            <div class="mt-6">
                                <label for="placa" class="block text-sm font-medium leading-6 text-gray-900">Número de Placa</label>
                                <div class="mt-2">
                                    <input type="text"
                                           wire:model="placa"
                                           x-ref="inputPlaca"
                                           wire:keydown.enter="crearTicket"
                                           class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-lg sm:leading-6 uppercase"
                                           placeholder="EJ: 2045-XTY">
                                    @error('placa') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                            wire:click="crearTicket"
                            class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                        Registrar Entrada
                    </button>
                    <button type="button"
                            x-on:click="open = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
