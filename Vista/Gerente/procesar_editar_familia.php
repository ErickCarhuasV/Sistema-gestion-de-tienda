<?php 
session_start(); // Inicia la sesión

require_once '../../Conexion/bd.php'; // Incluir la clase de conexión
require_once 'productoDao.php'; // Incluir el DAO de familia

$conexionBD = new ConexionBD();
$familiaDao = new FamiliaDao($conexionBD->getConexionBD());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las claves existen en el array $_POST
    if (isset($_POST['idFamilia'], $_POST['descripcionFamilia'], $_POST['idLinea'])) {
        $idFamilia = $_POST['idFamilia']; // Obtener el ID de la familia
        $descripcionFamilia = $_POST['descripcionFamilia']; // Obtener la nueva descripción
        $idLinea = $_POST['idLinea']; // Obtener el ID de la línea

        // Llamar a la función para actualizar la familia
        if ($familiaDao->editarFamilia($idFamilia, $descripcionFamilia, $idLinea)) {
            header("Location: familia.php"); // Redirige a la página de familias
            exit; // Asegúrate de salir después de la redirección
        } else {
            echo "Error al actualizar la familia.";
        }
    } else {
        echo "Error: Falta información en el formulario.";
    }
} else {
    echo "Error: Método de solicitud no válido.";
}
?>


