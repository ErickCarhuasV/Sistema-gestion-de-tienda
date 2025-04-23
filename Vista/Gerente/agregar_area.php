<?php


require_once '../../Conexion/bd.php'; // Incluir la clase de conexión
require_once 'productoDao.php'; // Incluir el DAO de área

$conexionBD = new ConexionBD();
$areaDao = new AreaDao($conexionBD->getConexionBD());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['area_agregada'])) {
        echo "Ya se ha agregado un área.";
        exit; // Termina el script para evitar que se ejecute el código adicional
    }

    $descripcion = $_POST['descripcion']; // Obtener la descripción del área

    // Llamar a la función para agregar el área
    if ($areaDao->agregarArea($descripcion)) {
        $_SESSION['area_agregada'] = true; // Marca que el área ha sido agregada
        header("Location: area.php"); // Redirige a la página de áreas
        exit; // Asegúrate de salir después de la redirección
    } else {
        echo "Error al agregar el área.";
    }
}
?>
