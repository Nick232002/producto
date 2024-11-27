<?php
session_start();
require_once 'config/config.php';
require_once 'class/producto.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();
$producto = new Producto($conn);

// Manejar acción de eliminar
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['idproducto'])) {
    $id = intval($_GET['idproducto']);

    if ($producto->eliminarProducto($id)) {
        $mensaje = "Producto eliminado correctamente.";
    } else {
        $error = "Error al eliminar el producto.";
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
    <title>Eliminar Productos</title>
</head>
<body>
    <h1>Gestión de Productos</h1>
    <a href="logout.php">Cerrar sesión</a>

    <?php if (isset($mensaje)): ?>
        <p class="mensaje"><?= $mensaje; ?></p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error; ?></p>
    <?php endif; ?>

    <h2>Lista de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $prod): ?>
                <tr>
                    <td><?= $prod['idproducto']; ?></td>
                    <td><?= htmlspecialchars($prod['nombre']); ?></td>
                    <td><?= htmlspecialchars($prod['descripcion']); ?></td>
                    <td><?= $prod['precio']; ?></td>
                    <td>
                        <!-- Enlace para eliminar -->
                        <a href="?action=delete&idproducto=<?= $prod['idproducto']; ?>" 
                           onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="producto.php">regresar</a>
</body>
</html>
