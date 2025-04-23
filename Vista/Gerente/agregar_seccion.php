<?php


require_once '../../Conexion/bd.php'; // Incluir la clase de conexión
require_once 'productoDao.php'; // Incluir el DAO de sección

$conexionBD = new ConexionBD();
$seccionDao = new SeccionDao($conexionBD->getConexionBD());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['seccion_agregada'])) {
        echo "Ya se ha agregado una sección.";
        exit; // Termina el script para evitar que se ejecute el código adicional
    }

    $descripcion = $_POST['descripcion']; // Obtener la descripción de la sección
    $id_area = $_POST['id_area']; // Obtener el ID del área

    // Llamar a la función para agregar la sección
    if ($seccionDao->agregarSeccion($descripcion, $id_area)) {
        $_SESSION['seccion_agregada'] = true; // Marca que la sección ha sido agregada
        header("Location: seccion.php"); // Redirige a la página de secciones
        exit; // Asegúrate de salir después de la redirección
    } else {
        echo "Error al agregar la sección.";
    }
}
?>





