<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>

<h2>Registrar Usuario</h2>

@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('register.post') }}">
    @csrf

    <label>Nombre:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Repetir contraseña:</label><br>
    <input type="password" name="password_confirmation" required><br><br>

    <label>Tipo Usuario:</label><br>
    <select name="tipo_usuario_id" required>
        @foreach ($tipos as $t)
            <option value="{{ $t->id }}">{{ $t->tipo }}</option>
        @endforeach
    </select><br><br>

    <button type="submit">Registrarse</button>
</form>

<br>
<a href="{{ route('login') }}">Volver al login</a>

</body>
</html>
