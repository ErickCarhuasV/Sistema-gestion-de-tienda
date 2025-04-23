<?php
require_once 'productoDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $productoDAO = new ProductoDAO();
    $id = $data['id'];
    $nombre = $data['nombre'];
    $descripcion = $data['descripcion'];
    $precio = $data['precio'];
    $cantidad = $data['cantidad'];
    $tipo = $data['tipo'];
    $marca = $data['marca'];
    $subfamilia = $data['subfamilia'];

    if ($productoDAO->actualizarProducto($id, $nombre, $descripcion, $precio, $tipo, $marca, $subfamilia, $cantidad)) {
        echo "Producto actualizado con éxito.";
    } else {
        http_response_code(500);
        echo "Error al actualizar el producto.";
    }
}
?>
