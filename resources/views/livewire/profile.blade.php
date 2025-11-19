<div class="max-w-4xl mx-auto p-6">
    <!-- Profile Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold gold-text">Mi Perfil</h1>
            <p class="text-gray-400">Gestiona tu información personal y configuración de seguridad.</p>
        </div>
        <div class="w-16 h-16 gold-gradient rounded-full flex items-center justify-center">
            <span class="text-2xl text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-gray-800 rounded-xl p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-white">Información Personal</h2>
            @if(!$editing)
                <button wire:click="editProfile"
                        class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-edit mr-2"></i> Editar
                </button>
            @endif
        </div>

        @if($editing)
            <form wire:submit.prevent="updateProfile" class="space-y-4">
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="name">Nombre</label>
                    <input wire:model="name" type="text" id="name"
                           class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="email">Email</label>
                    <input wire:model="email" type="email" id="email"
                           class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="tipo_usuario_id">Tipo de Usuario</label>
                    <select wire:model="tipo_usuario_id" id="tipo_usuario_id"
                            class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        @foreach($tiposUsuario as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_usuario_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" wire:click="$set('editing', false)"
                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition">
                        Guardar
                    </button>
                </div>
            </form>
        @else
            <div class="space-y-4">
                <div>
                    <p class="text-gray-400 text-sm">Nombre</p>
                    <p class="text-white text-lg">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Email</p>
                    <p class="text-white text-lg">{{ Auth::user()->email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Tipo de Usuario</p>
                    <p class="text-white text-lg">{{ Auth::user()->tipo->tipo ?? 'N/A' }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Password Section -->
    <div class="bg-gray-800 rounded-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-white">Seguridad</h2>
            @if(!$passwordEditing)
                <button wire:click="editPassword"
                        class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-lock mr-2"></i> Cambiar Contraseña
                </button>
            @endif
        </div>

        @if($passwordEditing)
            <form wire:submit.prevent="updatePassword" class="space-y-4">
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="current_password">Contraseña Actual</label>
                    <input wire:model="current_password" type="password" id="current_password"
                           class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    @error('current_password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="new_password">Nueva Contraseña</label>
                    <input wire:model="new_password" type="password" id="new_password"
                           class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    @error('new_password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="new_password_confirmation">Confirmar Nueva Contraseña</label>
                    <input wire:model="new_password_confirmation" type="password" id="new_password_confirmation"
                           class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" wire:click="$set('passwordEditing', false)"
                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition">
                        Guardar
                    </button>
                </div>
            </form>
        @else
            <div class="space-y-4">
                <p class="text-gray-400">Cambia tu contraseña regularmente para mantener tu cuenta segura.</p>
            </div>
        @endif
    </div>

    <!-- Success Message -->
    @if(session()->has('message'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg">
        {{ session('message') }}
    </div>
    @endif
</div>
