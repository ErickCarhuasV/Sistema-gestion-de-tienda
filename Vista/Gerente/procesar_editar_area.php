<?php
session_start();
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$areaDao = new AreaDao($conexionBD->getConexionBD());

// Obtiene el ID del área a editar desde los parámetros GET
$idArea = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion']; // Obtener la descripción del área

    // Llama a la función para editar el área
    if ($areaDao->editarArea($idArea, $descripcion)) {
        header("Location: area.php"); // Redirige a la página de áreas
        exit;
    } else {
        echo "Error al actualizar el área.";
    }
}
?>
