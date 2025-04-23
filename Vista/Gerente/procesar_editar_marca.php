<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$marcaDao = new MarcaDao($conexionBD->getConexionBD());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idMarca = $_POST['idMarca'];
    $descripcion = $_POST['descripcion'];
    $marcaDao->editarMarca($idMarca, $descripcion);
}

header("Location: marca.php");
exit();
?>
