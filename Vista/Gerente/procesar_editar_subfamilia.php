<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$subfamiliaDao = new SubfamiliaDao($conexionBD->getConexionBD());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idSubfamilia = $_POST['idSubfamilia'];
    $descripcionSubfamilia = $_POST['descripcionSubfamilia'];
    $idFamilia = $_POST['idFamilia'];

    if ($subfamiliaDao->editarSubfamilia($idSubfamilia, $descripcionSubfamilia, $idFamilia)) {
        echo "Subfamilia actualizada correctamente.";
        header("Location: subfamilia.php"); // Redirige a la lista de subfamilias
        exit();
    } else {
        echo "Error al actualizar la subfamilia.";
    }
}



