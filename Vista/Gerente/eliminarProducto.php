<?php
require_once 'productoDao.php'; // Incluir el DAO de producto
$productoDAO = new ProductoDAO();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idProducto = $_POST['id'];

    if ($productoDAO->eliminarProductoConDetalles($idProducto)) {
        echo "Producto eliminado con éxito.";
    } else {
        echo "Error al eliminar el producto123.";
    }
}
?>