<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['correoElectronico'])) {
    echo "No se ha iniciado sesión.";
    exit();
}

// Incluir los archivos necesarios
require_once '../../Conexion/bd.php';
require_once '../../Modelo/UsuarioDao.php';
require_once '../../Modelo/UsuarioBean.php';

// Crear instancia de la conexión y el DAO
$conn = new Conexion();
$usuarioDao = new UsuarioDao($conn->conn);

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];

// Actualizar los datos del usuario
$usuarioBean = new UsuarioBean($correo);
$usuarioData = $usuarioDao->obtenerUsuarioPorCorreo($correo);

// Verificar si el usuario existe
if ($usuarioData) {
    $usuarioBean->setIdUsuario($usuarioData['IDUSUARIO']);
    
    // Actualizar el nombre del usuario
    $sql = "UPDATE USUARIO SET NOMBRES = :nombre WHERE IDUSUARIO = :idUsuario";
    $stmt = $conn->conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':idUsuario', $usuarioBean->getIdUsuario());
    $stmt->execute();

    echo "Datos actualizados correctamente.";
} else {
    echo "El usuario no existe.";
}

?>
