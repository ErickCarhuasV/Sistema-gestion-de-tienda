<?php
session_start();
require_once 'productoDao.php';

if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$productoDAO = new ProductoDAO();

?>
<h3>Productos Seleccionados</h3>
<ul>
    <?php if (empty($_SESSION['carrito'])): ?>
        <li>No hay productos seleccionados en el carrito.</li>
    <?php else: ?>
        <?php foreach ($_SESSION['carrito'] as $item): ?>
            <?php 
            // Validar que el item tiene los campos necesarios
            if (!isset($item['id'], $item['cantidad']) || !is_int($item['cantidad'])) {
                continue;
            }
            
            // Obtener producto desde la base de datos
            $producto = $productoDAO->buscarProductoPorId($item['id']); 
            
            // Validar si el producto existe
            if (!$producto): ?>
                <li>Producto no encontrado (ID: <?= htmlspecialchars($item['id']) ?>)</li>
            <?php else: ?>
                <li><?= htmlspecialchars($producto->getNombre()) ?> - Cantidad: <?= intval($item['cantidad']) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
