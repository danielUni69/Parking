<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TipoUsario;

class Profile extends Component
{
    public $name;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $tipo_usuario_id;
    public $tiposUsuario;
    public $editing = false;
    public $passwordEditing = false;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->tipo_usuario_id = Auth::user()->tipo_usuario_id;
        $this->tiposUsuario = TipoUsario::all();
    }

    public function editProfile()
    {
        $this->editing = true;
    }

    public function editPassword()
    {
        $this->passwordEditing = true;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'tipo_usuario_id' => 'required|exists:tipo_usuario,id',
        ]);

        $user = User::find(Auth::id());
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'tipo_usuario_id' => $this->tipo_usuario_id,
        ]);

        $this->editing = false;
        session()->flash('message', 'Perfil actualizado correctamente.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'La contraseña actual es incorrecta.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->passwordEditing = false;
        session()->flash('message', 'Contraseña actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
