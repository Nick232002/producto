<?php
require_once 'config/config.php';
require_once 'class/usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $correo = htmlspecialchars($_POST['correo']);
    $contrasena = htmlspecialchars($_POST['contrasena']);

    $database = new Database();
    $conn = $database->getConnection();

    $usuario = new Usuario($conn);

    if ($usuario->registrarUsuario($nombre, $correo, $contrasena)) {
        echo "Registro exitoso. Ahora puedes iniciar sesión.";
    } else {
        echo "Error al registrar usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registro</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br>

        <button type="submit">Registrarse</button>
        <a href="login.php">Inicie sesion</a>
    </form>
</body>
</html>
