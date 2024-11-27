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

$error = "";
$prod = null;

// Buscar producto por ID
if (isset($_POST['buscar_id']) && is_numeric($_POST['buscar_id'])) {
    $idproducto = intval($_POST['buscar_id']);
    $prod = $producto->obtenerProductoPorId($idproducto);
    if (!$prod) {
        $error = "Producto no encontrado. Verifica el ID.";
    }
}

// Editar producto
if (isset($_POST['editar'])) {
    $idproducto = intval($_POST['idproducto']);
    $nombre = htmlspecialchars($_POST['nombre']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $precio = floatval($_POST['precio']);

    if ($producto->actualizarProducto($idproducto, $nombre, $descripcion, $precio)) {
        echo "Producto actualizado correctamente.";
        header("Location: producto.php");
        exit();
    } else {
        $error = "Error: No se pudo actualizar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto por ID</title>
</head>
<body>
    <h1>Editar Producto por ID</h1>

    <!-- Formulario para buscar producto por ID -->
    <form method="POST">
        <label for="buscar_id">ID del Producto:</label>
        <input type="number" id="buscar_id" name="buscar_id" required>
        <button type="submit">Buscar Producto</button>
    </form>

    <!-- Mostrar errores -->
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- Formulario para editar el producto si se encuentra -->
    <?php if ($prod): ?>
        <form method="POST">
            <input type="hidden" name="idproducto" value="<?= htmlspecialchars($prod['idproducto']); ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($prod['nombre']); ?>" required>

            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" value="<?= htmlspecialchars($prod['descripcion']); ?>" required>

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" value="<?= $prod['precio']; ?>" required>

            <button type="submit" name="editar">Guardar Cambios</button>
        </form>
    <?php endif; ?>

    <a href="producto.php">Volver a la lista de productos</a>
</body>
</html>
