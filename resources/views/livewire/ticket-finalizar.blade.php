<div>
    <div
        x-data="{ open: false }"
        x-on:abrir-modal-finalizar.window="open = true"
        x-on:cerrar-modal-finalizar.window="open = false"
        x-on:keydown.escape.window="open = false"
        style="display: none;"
        x-show="open"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">

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
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">

                {{-- Header con color diferente para distinguir salida --}}
                <div class="bg-green-600 px-4 py-4 sm:px-6">
                    <h3 class="text-lg font-semibold leading-6 text-white flex items-center gap-2" id="modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                        Finalizar Ticket
                    </h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-500">Espacio</span>
                            <span class="text-xl font-bold text-gray-900">{{ $espacio->codigo ?? '' }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-500">Placa</span>
                            <span class="text-lg font-mono font-bold text-gray-900">{{ $ticket->placa ?? '' }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-500">Tiempo Transcurrido</span>
                            <span class="text-gray-900 font-medium">{{ number_format($minutos ?? 0, 0) }} min ({{ number_format($horas ?? 0, 2) }} hrs)</span>
                        </div>

                        <div class="mt-4 bg-green-50 p-4 rounded-lg border border-green-100 text-center">
                            <p class="text-sm text-green-800 mb-1">Total a Pagar</p>
                            <p class="text-3xl font-bold text-green-700">{{ number_format($monto ?? 0, 2) }} Bs</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                            wire:click="finalizar"
                            class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto">
                        Cobrar e Imprimir
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
