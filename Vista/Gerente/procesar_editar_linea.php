<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$lineaDao = new LineaDao($conexionBD->getConexionBD());

// Obtener los datos enviados desde el formulario
$idLinea = $_POST['idLinea'];
$descripcionLinea = $_POST['descripcionLinea'];
$idSeccion = $_POST['idSeccion'];

// Llamar a la función de edición en el DAO
$resultado = $lineaDao->editarLinea($idLinea, $descripcionLinea, $idSeccion);

if ($resultado) {
    echo "Línea actualizada exitosamente.";
    // Redirigir a la lista de líneas o mostrar un mensaje de éxito
    header("Location: linea.php?mensaje=actualizado");
    exit;
} else {
    echo "Error al actualizar la línea.";
}
?>
