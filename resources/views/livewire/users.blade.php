<div class="p-6">
    <!-- Search and Add User -->
    <div class="flex justify-between items-center mb-6">
        <div class="w-1/3">
            <input type="text" wire:model.live="search" placeholder="Buscar usuario..."
                   class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
        </div>
        <button wire:click="confirmUserAddition"
                class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition">
            <i class="fas fa-plus mr-2"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $user->tipo->tipo ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button wire:click="confirmUserEdition({{ $user->id }})"
                                class="text-yellow-400 hover:text-yellow-300 mr-4">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button wire:click="confirmUserDeletion({{ $user->id }})"
                                class="text-red-400 hover:text-red-300">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-400">
                        No se encontraron usuarios.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Add/Edit User Modal -->
    @if($confirmingUserAddition || $confirmingUserEdition)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-xl font-bold text-white mb-4">
                {{ $confirmingUserAddition ? 'Nuevo Usuario' : 'Editar Usuario' }}
            </h2>
            <form wire:submit.prevent="saveUser">
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="name">Nombre</label>
                    <input wire:model="name" type="text" id="name"
                           class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="email">Email</label>
                    <input wire:model="email" type="email" id="email"
                           class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="password">Contraseña</label>
                    <input wire:model="password" type="password" id="password"
                           class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    @error('password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm font-bold mb-2" for="tipo_usuario_id">Tipo de Usuario</label>
                    <select wire:model="tipo_usuario_id" id="tipo_usuario_id"
                            class="w-full px-3 py-2 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <option value="">Seleccione un tipo</option>
                        @foreach($tiposUsuario as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_usuario_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" wire:click="resetInputFields; $set('confirmingUserAddition', false); $set('confirmingUserEdition', false)"
                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 gold-gradient text-black font-semibold rounded-lg hover:shadow-lg transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete User Modal -->
    @if($confirmingUserDeletion)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 w-1/3">
            <h2 class="text-xl font-bold text-white mb-4">Eliminar Usuario</h2>
            <p class="text-gray-300 mb-6">¿Estás seguro de que deseas eliminar este usuario?</p>
            <div class="flex justify-end space-x-4">
                <button wire:click="$set('confirmingUserDeletion', false)"
                        class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition">
                    Cancelar
                </button>
                <button wire:click="deleteUser"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Success Message -->
    @if(session()->has('message'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg">
        {{ session('message') }}
    </div>
    @endif
</div>
