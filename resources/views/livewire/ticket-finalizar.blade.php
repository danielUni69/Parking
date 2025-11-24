<div>
    <div x-data="{ open: false }" x-on:abrir-modal-finalizar.window="open = true"
        x-on:cerrar-modal-finalizar.window="open = false" x-on:keydown.escape.window="open = false"
        style="display: none;" x-show="open"
        class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-500 px-4 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white rounded-full">
                        <i class="fas fa-money-bill-wave text-yellow-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white" id="modal-title">
                        Finalizar Ticket
                    </h3>
                </div>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-5">
                    @if($ticket)
                        <div class="flex justify-between items-center border-b border-gray-700 pb-3">
                            <span class="text-gray-400">Espacio</span>
                            <span class="text-xl font-bold text-white">{{ $espacio->codigo ?? '' }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-700 pb-3">
                            <span class="text-gray-400">Placa</span>
                            <span class="text-lg font-mono font-bold text-white">{{ $ticket->placa ?? '' }}</span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-700 pb-3">
                            <span class="text-gray-400">Hora Ingreso</span>
                            <span class="text-white font-medium">
                                {{ \Carbon\Carbon::parse($ticket->horaIngreso)->setTimezone('America/La_Paz')->format('H:i') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-700 pb-3">
                            <span class="text-gray-400">Hora Salida</span>
                            <span class="text-white font-medium">
                                {{ now()->setTimezone('America/La_Paz')->format('H:i') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center border-b border-gray-700 pb-3">
                            <span class="text-gray-400">Tiempo Transcurrido</span>
                            <span class="text-white font-medium">
                                {{ number_format($minutos ?? 0, 0) }} min ({{ number_format($horas ?? 0, 2) }} hrs)
                            </span>
                        </div>

                        <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600 grid grid-cols-2 gap-4 text-center">
                            <div>
                                <p class="text-yellow-400 font-semibold">Tarifa Diurna</p>
                                <p class="text-lg font-bold text-yellow-500">Bs. {{ number_format($tarifaBase ?? 0, 2) }}</p>
                                <p class="text-xs text-gray-400">6:00 - 17:59</p>
                            </div>
                            <div>
                                <p class="text-purple-400 font-semibold">Tarifa Nocturna</p>
                                <p class="text-lg font-bold text-purple-500">Bs. {{ number_format($tarifaNocturna ?? 0, 2) }}</p>
                                <p class="text-xs text-gray-400">18:00 - 05:59</p>
                            </div>
                        </div>

                        @if(isset($desgloseHoras))
                        <div class="bg-gray-700/50 p-4 rounded-lg border border-gray-600 mt-3 grid grid-cols-2 gap-4 text-center">
                            <div>
                                <p class="text-gray-400 font-medium">Horas Diurnas</p>
                                <p class="font-bold text-white text-lg">{{ $desgloseHoras['normales'] ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 font-medium">Horas Nocturnas</p>
                                <p class="font-bold text-white text-lg">{{ $desgloseHoras['nocturnas'] ?? 0 }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="mt-4 bg-yellow-900/50 p-5 rounded-lg border border-yellow-800 text-center">
                            <p class="text-sm text-yellow-300 mb-1">Total a Pagar</p>
                            <p class="text-3xl font-bold text-yellow-400">{{ number_format($monto ?? 0, 2) }} Bs</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-700/80 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button"
                        wire:click="finalizar"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 sm:ml-3 sm:w-auto">
                    Cobrar e Imprimir
                </button>
                <button type="button"
                        x-on:click="open = false"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-gray-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 sm:mt-0 sm:w-auto">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
