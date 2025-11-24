<div>
    <div x-data="{ open: false }" x-on:abrir-modal-finalizar.window="open = true"
        x-on:cerrar-modal-finalizar.window="open = false" x-on:keydown.escape.window="open = false"
        style="display: none;" x-show="open"
        class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        {{-- Contenido del Modal --}}
        <div x-show="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-gray-800 border border-gray-700 rounded-lg w-full max-w-md">

            {{-- Header con el color deseado --}}
            <div class="bg-yellow-600 px-4 py-4 border-b border-gray-700">
                <h3 class="text-lg font-semibold leading-6 text-white flex items-center gap-2" id="modal-title">
                    <i class="fas fa-money-bill-wave text-yellow-500"></i>
                    Finalizar Ticket
                </h3>
            </div>

            <div class="px-4 py-5 sm:p-6 space-y-4">
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-700 pb-2">
                        <span class="text-gray-400">Espacio</span>
                        <span class="text-xl font-bold text-white">{{ $espacio->codigo ?? '' }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-700 pb-2">
                        <span class="text-gray-400">Placa</span>
                        <span class="text-lg font-mono font-bold text-white">{{ $ticket->placa ?? '' }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-700 pb-2">
                        <span class="text-gray-400">Hora Ingreso</span>
                        <span class="text-white font-medium">
                            @if($ticket)
                            {{ \Carbon\Carbon::parse($ticket->horaIngreso)->format('H:i') }}
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-700 pb-2">
                        <span class="text-gray-400">Hora Salida</span>
                        <span class="text-white font-medium">
                            {{ now()->format('H:i') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-700 pb-2">
                        <span class="text-gray-400">Tiempo Transcurrido</span>
                        <span class="text-white font-medium">{{ number_format($minutos ?? 0, 0) }} min ({{
                            number_format($horas ?? 0, 2) }} hrs)</span>
                    </div>

                    {{-- Información de tarifas --}}
                    <div class="bg-gray-700/50 p-3 rounded-lg border border-gray-600">
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="text-center">
                                <p class="text-yellow-400 font-semibold">Tarifa Normal</p>
                                <p class="text-lg font-bold text-yellow-500">Bs. {{ number_format($tarifaBase ?? 0, 2)
                                    }}/h</p>
                                <p class="text-xs text-gray-400">6:00 - 17:59</p>
                            </div>
                            <div class="text-center">
                                <p class="text-purple-400 font-semibold">Tarifa Nocturna</p>
                                <p class="text-lg font-bold text-purple-500">Bs. {{ number_format($tarifaNocturna ?? 0,
                                    2) }}/h</p>
                                <p class="text-xs text-gray-400">18:00 - 5:59 (+2 Bs)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Desglose del cálculo si está disponible --}}
                    @if(isset($desgloseHoras))
                    <div class="bg-gray-700/50 p-3 rounded-lg border border-gray-600">
                        <p class="text-sm font-semibold text-gray-300 mb-2">Desglose:</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="text-center">
                                <p class="text-gray-400">Horas Normales</p>
                                <p class="font-bold text-white">{{ $desgloseHoras['normales'] ?? 0 }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-400">Horas Nocturnas</p>
                                <p class="font-bold text-white">{{ $desgloseHoras['nocturnas'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4 bg-yellow-900/50 p-4 rounded-lg border border-yellow-800 text-center">
                        <p class="text-sm text-yellow-300 mb-1">Total a Pagar</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ number_format($monto ?? 0, 2) }} Bs</p>
                        <p class="text-xs text-yellow-500 mt-1">Incluye tarifa variable por horario</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 p-4 border-t border-gray-700">
                <button type="button" x-on:click="open = false"
                    class="px-4 py-2 text-gray-300 border border-gray-600 rounded hover:bg-gray-700 transition-colors text-sm">
                    Cancelar
                </button>
                <button type="button" wire:click="finalizar"
                    class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors text-sm flex items-center gap-2">
                    <i class="fas fa-print"></i>
                    Cobrar e Imprimir
                </button>
            </div>
        </div>
    </div>
</div>
