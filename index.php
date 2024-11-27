<?php
require_once 'config/config.php';
require_once 'class/usuario.php';
require_once 'class/producto.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Instancias de clases
$producto = new Producto($conn);

// Manejar acciones de productos (Agregar, Editar, Eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $nombre = htmlspecialchars($_POST['nombre']);
            $descripcion = htmlspecialchars($_POST['descripcion']);
            $precio = floatval($_POST['precio']);
            $stock = intval($_POST['stock']);
            $producto->agregarProducto($nombre, $descripcion, $precio, $stock);
        } elseif ($_POST['action'] === 'edit') {
            $id = intval($_POST['id']);
            $nombre = htmlspecialchars($_POST['nombre']);
            $descripcion = htmlspecialchars($_POST['descripcion']);
            $precio = floatval($_POST['precio']);
            $stock = intval($_POST['stock']);
            $producto->actualizarProducto($id, $nombre, $descripcion, $precio, $stock);
        } elseif ($_POST['action'] === 'delete') {
            $id = intval($_POST['id']);
            $producto->eliminarProducto($id);
        }
    }
}

// Obtener todos los productos
$productos = $producto->leerTodosLosProductos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    
</head>
<body>
    <h1>Gestión de Productos</h1>
    <p>Bienvenido, usuario.</p>

    <h2>Agregar Producto</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" required>
        <label for="precio">Precio:</label>
        <input type="number" step="0.01" id="precio" name="precio" required>
        <button type="submit">Agregar</button>
    </form>

    <h2>Lista de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $prod): ?>
                <tr>
                    <td><?= $prod['id']; ?></td>
                    <td><?= htmlspecialchars($prod['nombre']); ?></td>
                    <td><?= htmlspecialchars($prod['descripcion']); ?></td>
                    <td><?= $prod['precio']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
