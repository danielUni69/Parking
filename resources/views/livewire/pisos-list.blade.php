<div class="flex items-center overflow-x-auto max-w-full">
    @foreach ($pisos as $piso)
        <div class="relative group p-2">
            <button
                wire:click="seleccionarPiso({{ $piso->id }})"
                class="{{ $pisoSeleccionado == $piso->id ? 'bg-yellow-600 ring-2 ring-yellow-400' : 'bg-yellow-500'}}
                       text-white font-semibold py-2 px-4 rounded-full shadow-md hover:bg-yellow-600 transition duration-300 transform hover:scale-105">
                Piso {{ $piso->numero }}
            </button>

            @auth
            <button
                wire:click.stop="intentarEliminarPiso({{ $piso->id }})"
                class="absolute -top-2 -right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow opacity-0 group-hover:opacity-100 transition-opacity"
                title="Eliminar Piso"
            >
                <i class="fas fa-times"></i>
            </button>
            @endauth
        </div>
    @endforeach

    @auth
    <button wire:click="abrirModal" class="bg-gray-700 hover:bg-green-600 text-gray-300 hover:text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors shadow-inner" title="Agregar Piso">
        <i class="fas fa-plus"></i>
    </button>
    @endauth
    @if($mostrarModal)
        <div class="fixed inset-0  mt-40 flex items-center justify-center z-[60] p-4 backdrop-blur-sm">
            <div class="bg-gray-800 border border-gray-700 rounded-lg w-full max-w-sm p-4 shadow-2xl">
                <h3 class="text-white font-bold mb-4">Nuevo Piso</h3>
                <div class="space-y-3">
                    <label class="text-white">El nuevo piso: 3</label>
                    @error('nuevoPisoNumero') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror

                    <input type="text" wire:model="nuevoPisoDescripcion" placeholder="Descripción (Opcional)" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2 focus:ring-1 focus:ring-yellow-500 outline-none">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button wire:click="$set('mostrarModal', false)" class="text-gray-400 text-sm hover:text-white px-3 py-1">Cancelar</button>
                    <button wire:click="crearPiso" class="bg-yellow-600 hover:bg-yellow-500 text-white rounded px-4 py-1 text-sm font-bold">Guardar</button>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {

            @this.on('swal:success_piso', (msj) => {
                Swal.fire({
                    title: '¡Éxito!',
                    text: msj,
                    icon: 'success',
                    background: '#1f2937', color: '#fff', confirmButtonColor: '#10b981', timer: 2000, showConfirmButton: false
                });
            });

            @this.on('swal:error_piso', (msj) => {
                Swal.fire({
                    title: 'Error',
                    text: msj,
                    icon: 'error',
                    background: '#1f2937', color: '#fff', confirmButtonColor: '#ef4444'
                });
            });

            @this.on('swal:confirm_piso', (id) => {
                Swal.fire({
                    title: '¿Eliminar Piso?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#374151',
                    confirmButtonText: 'Sí, eliminar',
                    background: '#1f2937', color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.dispatch('eliminarPisoConfirmado', { id: id });
                    }
                });
            });
        });
    </script>
</div>
