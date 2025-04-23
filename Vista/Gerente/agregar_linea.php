<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$lineaDao = new LineaDao($conexionBD->getConexionBD());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'];
    $idSeccion = $_POST['idSeccion'];

    $resultado = $lineaDao->agregarLinea($descripcion, $idSeccion);

    if ($resultado) {
        // Redirige a linea.php con un mensaje de éxito
        header("Location: linea.php?mensaje=agregado");
        exit;
    } else {
        echo "Error al agregar la línea";
    }
}
?>


