<?php
require_once 'productoDao.php';

$productoDAO = new ProductoDAO();
$producto = null;

// Verificar si el ID del producto fue pasado por URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $producto = $productoDAO->obtenerProductoPorID($id);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];
    $marca = $_POST['marca'];
    $subfamilia = $_POST['subfamilia'];
    $cantidad = $_POST['cantidad'];

    // Validación simple
    if (empty($nombre) || empty($descripcion) || !is_numeric($precio) || !is_numeric($cantidad)) {
        echo "Por favor, completa todos los campos correctamente.";
    } else {
        if ($productoDAO->actualizarProducto($id, $nombre, $descripcion, $precio, $tipo, $marca, $subfamilia, $cantidad)) {
            // Redirigir a la lista de productos después de actualizar
            header("Location: productos.php");
            exit();
        } else {
            echo "Error al actualizar el producto.";
        }
    }
}

// Obtener la lista de subfamilias para mostrar en el formulario
$conexionBD = new ConexionBD();
$subfamiliaDao = new SubfamiliaDao($conexionBD->getConexionBD());
$subfamilias = $subfamiliaDao->listarSubfamilias();

// Obtener la lista de marcas y tipos para mostrar en el formulario
$marcaDao = new MarcaDao($conexionBD->getConexionBD());
$tipoDao = new TipoDao($conexionBD->getConexionBD());
$marcas = $marcaDao->listarMarcas();
$tipos = $tipoDao->listarTipos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar.css">
    <title>Editar Producto</title>
</head>
<body>
    <h2>Editar Producto</h2>

    <?php if ($producto): ?>
        <form method="POST" action="editar_producto.php?id=<?php echo $producto->getIdProducto(); ?>">
            <input type="hidden" name="id" value="<?php echo htmlentities($producto->getIdProducto()); ?>">
            <input type="text" name="nombre" value="<?php echo htmlentities($producto->getNombre()); ?>" required>
            <input type="text" name="descripcion" value="<?php echo htmlentities($producto->getDescripcion()); ?>" required>
            <input type="number" name="precio" value="<?php echo htmlentities($producto->getPrecio()); ?>" required>
            <input type="number" name="cantidad" value="<?php echo htmlentities($producto->getCantidad()); ?>" required>

            <!-- Campo de Tipo -->
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <?php foreach ($tipos as $tipo): ?>
                    <option value="<?= htmlentities($tipo->getIdTipo()) ?>" <?= $tipo->getIdTipo() == $producto->getTipo() ? 'selected' : '' ?>>
                        <?= htmlentities($tipo->getDescripcion()) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Campo de Marca -->
            <label for="marca">Marca:</label>
            <select id="marca" name="marca" required>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= htmlentities($marca->getIdMarca()) ?>" <?= $marca->getIdMarca() == $producto->getMarca() ? 'selected' : '' ?>>
                        <?= htmlentities($marca->getDescripcion()) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Campo de Subfamilia -->
            <label for="subfamilia">Subfamilia:</label>
            <select id="subfamilia" name="subfamilia" required>
                <?php foreach ($subfamilias as $subfamilia): ?>
                    <option value="<?= htmlentities($subfamilia->getIdSubfamilia()) ?>" <?= $subfamilia->getIdSubfamilia() == $producto->getSubfamilia() ? 'selected' : '' ?>>
                        <?= htmlentities($subfamilia->getDescripcionSubfamilia()) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Actualizar Producto</button>
        </form>
    <?php else: ?>
        <p>Producto no encontrado.</p>
    <?php endif; ?>
</body>
</html>
