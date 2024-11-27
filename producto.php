<?php
session_start();
require_once 'config/config.php';
require_once 'class/usuario.php';
require_once 'class/producto.php';
// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Gestión de productos
$producto = new Producto($conn);
$productos = $producto->leerTodosLosProductos();

// Procesar solicitudes de agregar, editar y eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar'])) {
        $producto->insertarProducto($_POST['nombre'], $_POST['descripcion'], $_POST['precio']);
    } elseif (isset($_POST['editar'])) {
        $producto->actualizarProducto($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio']);
    } elseif (isset($_POST['eliminar'])) {
        $producto->eliminarProducto($_POST['id']);
    }
    header("Location: producto.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
</head>
<body>
    <h2>Gestión de Productos</h2>

    <h3>Agregar Producto</h3>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre del producto" required>
        <textarea name="descripcion" placeholder="Descripción" required></textarea>
        <input type="number" name="precio" placeholder="Precio" step="0.01" required>
        <button type="submit" name="agregar">Agregar</button>
    </form>

    <h3>Lista de Productos</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Acciones de producto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo $producto['idproducto']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo $producto['descripcion']; ?></td>
                    <td><?php echo $producto['precio']; ?></td>  
                    <td><a href="eliminar.php">Eliminar</a></td> 
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="editar.php">Editar</a>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
