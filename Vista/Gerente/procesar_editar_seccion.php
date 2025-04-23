<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$seccionDao = new SeccionDao($conexionBD->getConexionBD());

// Obtener los datos enviados desde el formulario
$idSeccion = $_POST['idSeccion'];
$descripcionSeccion = $_POST['descripcionSeccion'];
$idArea = $_POST['idArea'];

// Llamar a la función de edición en el DAO
$resultado = $seccionDao->editarSeccion($idSeccion, $descripcionSeccion, $idArea);

if ($resultado) {
    header("Location: seccion.php?mensaje=actualizado");
    exit;
} else {
    echo "Error al actualizar la sección.";
}
?>




