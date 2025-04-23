<?php
require_once '../../Conexion/bd.php'; // Incluir la clase de conexión
require_once 'productoDao.php'; // Incluir el DAO de subfamilia

$conexionBD = new ConexionBD();
$subfamiliaDao = new SubfamiliaDao($conexionBD->getConexionBD());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcionSubfamilia = $_POST['descripcion'];
    $idFamilia = $_POST['idFamilia'];

    if ($subfamiliaDao->agregarSubfamilia($descripcionSubfamilia, $idFamilia)) {
        echo "Subfamilia agregada exitosamente.";
    } else {
        echo "Error al agregar subfamilia.";
    }
    header("Location: subfamilia.php");
    exit();
}
?>
