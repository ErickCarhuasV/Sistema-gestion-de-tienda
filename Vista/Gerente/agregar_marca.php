<?php
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$marcaDao = new MarcaDao($conexionBD->getConexionBD());

$descripcionMarca = $_POST['descripcionMarca'];

// Llamada al método del DAO para agregar la marca
$marcaDao->agregarMarca($descripcionMarca);

// Redirige de nuevo a la página de marcas
header("Location: marca.php");
exit;
?>

