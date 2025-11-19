<div class="flex">
    @foreach ($pisos as $piso)
        <button
            wire:click="seleccionarPiso({{ $piso->id }})"
            class="{{  $pisoSeleccionado == $piso->id ? 'bg-yellow-600' : 'bg-yellow-500'}} text-white font-semibold py-2 px-4 mx-2 rounded-full shadow-md hover:bg-yellow-600 transition duration-300 transform hover:scale-105 cursor-default">
            Piso: {{ $piso->numero }}
        </button>
    @endforeach
</div>
