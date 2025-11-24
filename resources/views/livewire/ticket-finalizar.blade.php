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

                <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-4 py-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white" id="modal-title">
                            Finalizar Ticket
                        </h3>
                    </div>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-5">
                        @if($ticket)
                            <div class="flex justify-between items-center border-b pb-3">
                                <span class="text-gray-600">Espacio</span>
                                <span class="text-xl font-bold text-gray-900">{{ $espacio->codigo ?? '' }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b pb-3">
                                <span class="text-gray-600">Placa</span>
                                <span class="text-lg font-mono font-bold text-gray-900">{{ $ticket->placa ?? '' }}</span>
                            </div>

                            <div class="flex justify-between items-center border-b pb-3">
                                <span class="text-gray-600">Hora Ingreso</span>
                                <span class="text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($ticket->horaIngreso)->setTimezone('America/La_Paz')->format('H:i') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center border-b pb-3">
                                <span class="text-gray-600">Hora Salida</span>
                                <span class="text-gray-900 font-medium">
                                    {{ now()->setTimezone('America/La_Paz')->format('H:i') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center border-b pb-3">
                                <span class="text-gray-600">Tiempo Transcurrido</span>
                                <span class="text-gray-900 font-medium">
                                    {{ number_format($minutos ?? 0, 0) }} min ({{ number_format($horas ?? 0, 2) }} hrs)
                                </span>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="text-center p-2">
                                        <p class="text-blue-700 font-semibold">Tarifa Diurna</p>
                                        <p class="text-2xl font-bold text-blue-800">Bs. {{ number_format($tarifaBase ?? 0, 2) }}</p>
                                        <p class="text-xs text-blue-600">06:00 - 17:59</p>
                                    </div>
                                    <div class="text-center p-2">
                                        <p class="text-purple-700 font-semibold">Tarifa Nocturna</p>
                                        <p class="text-2xl font-bold text-purple-800">Bs. {{ number_format($tarifaNocturna ?? 0, 2) }}</p>
                                        <p class="text-xs text-purple-600">18:00 - 05:59</p>
                                    </div>
                                </div>
                            </div>

                            @if(isset($desgloseHoras))
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-sm font-semibold text-gray-700 mb-3">Desglose de Horas:</p>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="text-center p-2 bg-white rounded shadow-sm">
                                        <p class="text-gray-600 font-medium">Horas Diurnas</p>
                                        <p class="font-bold text-gray-800 text-lg">{{ $desgloseHoras['normales'] ?? 0 }}</p>
                                    </div>
                                    <div class="text-center p-2 bg-white rounded shadow-sm">
                                        <p class="text-gray-600 font-medium">Horas Nocturnas</p>
                                        <p class="font-bold text-gray-800 text-lg">{{ $desgloseHoras['nocturnas'] ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="mt-4 bg-green-50 p-5 rounded-lg border border-green-200 text-center">
                                <p class="text-sm text-green-800 mb-1">Total a Pagar</p>
                                <p class="text-3xl font-bold text-green-700">{{ number_format($monto ?? 0, 2) }} Bs</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                            wire:click="finalizar"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:ml-3 sm:w-auto">
                        Cobrar e Imprimir
                    </button>
                    <button type="button"
                            x-on:click="open = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
