<h1>Bienvenido al Dashboard</h1>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button>Cerrar sesión</button>
</form>
