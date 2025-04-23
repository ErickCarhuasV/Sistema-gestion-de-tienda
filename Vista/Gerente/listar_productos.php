<?php

header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
require_once 'productoDao.php'; // Incluir el DAO de producto

$productoDAO = new ProductoDAO();
$productos = $productoDAO->listarProductos();

foreach ($productos as $producto) {
    echo '<tr>';
    echo '<td>' . htmlentities($producto->getIdProducto()) . '</td>';
    echo '<td>' . htmlentities($producto->getNombre()) . '</td>';
    echo '<td>' . htmlentities($producto->getDescripcion()) . '</td>';
    echo '<td>' . htmlentities($producto->getPrecio()) . '</td>';
    echo '<td>' . htmlentities($producto->getCantidad()) . '</td>';
    echo '<td>' . htmlentities($producto->getTipo()) . '</td>';
    echo '<td>' . htmlentities($producto->getMarca()) . '</td>';
    echo '<td>' . htmlentities($producto->getSubfamilia()) . '</td>';
    echo '<td>' . htmlentities($producto->getFamilia()) . '</td>';
    echo '<td>' . htmlentities($producto->getLinea()) . '</td>';
    echo '<td>' . htmlentities($producto->getSeccion()) . '</td>';
    echo '<td>' . htmlentities($producto->getArea()) . '</td>';

    echo "<td>
    <button class='edit-button' onclick='editarProducto({$producto->getIdProducto()})'>✏️</button>
                   <button class='delete-button' onclick='eliminarProducto({$producto->getIdProducto()})'>❌</button>
</td>";
       echo '</tr>';
   }


?>
