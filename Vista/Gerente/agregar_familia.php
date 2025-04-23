<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php'; // Asegúrate de que este archivo está incluido


// Conectar a la base de datos
$conexionBD = new ConexionBD();
$familiaDao = new FamiliaDao($conexionBD->getConexionBD());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];
    $idLinea = $_POST['idLinea']; // Asegúrate de obtener el ID de la línea

    // Llamar a agregarFamilia con ambos parámetros
    $familiaDao->agregarFamilia($descripcion, $idLinea);

    // Redirigir o mostrar un mensaje de éxito
    header("Location: familia.php"); // Cambia esto según sea necesario
    exit();
}
?>

