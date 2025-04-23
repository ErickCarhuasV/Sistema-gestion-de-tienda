<?php
// Incluir la clase de conexión y el DAO
require_once '../../Conexion/bd.php';
require_once '../../Modelo/UsuarioDao.php';

// Crear una instancia de la conexión
$conexionBD = new ConexionBD();
$conn = $conexionBD->getConexionBD();

// Crear una instancia del DAO
$usuarioDao = new UsuarioDAO($conn);

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar todos los datos del formulario
    $idUsuario = $_POST['idUsuario'];
    $nombre = $_POST['nombre'];
    $apellidoPaterno = $_POST['apellidoPaterno'];
    $apellidoMaterno = $_POST['apellidoMaterno'];
    $celular = $_POST['celular'];
    $correoElectronico = $_POST['correoElectronico'];
    $clave = $_POST['clave'];

    // Llamar a la función de editarUsuario con los datos correctos
    $usuarioDao->editarUsuario($idUsuario, $nombre, $apellidoPaterno, $apellidoMaterno, $celular, $correoElectronico, $clave);

    // Redirigir a la página de configuración después de actualizar
    header("Location: configuracion.php");
    exit();
}


?>
