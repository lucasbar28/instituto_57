<h1>Login de Usuario</h1>

<form action="<?= base_url('auth') ?>" method="post">
    <label for="email">Nombre de usuario:</label>
    <input type="text" name="nombre_de_usuario" required>

    <label for="password">Contraseña:</label>
    <input type="password" name="contrasena" required>
    
    <button type="submit">Iniciar sesión</button>
</form> 