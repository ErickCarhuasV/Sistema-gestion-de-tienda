<?php
require_once 'UsuarioDao.php'; // Asegúrate de que esta ruta sea correcta

// Obtén la conexión a la base de datos
$conexion = (new ConexionBD())->getConexionBD();
$usuarioDao = new UsuarioDao($conexion);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'] ?? ''; // Determina si es una acción de actualizar o eliminar

        if ($accion === 'eliminar') {
            // Elimina el usuario si la acción es "eliminar"
            if (!is_numeric($_POST['idUsuario'])) {
                throw new Exception("ID de Usuario debe ser numérico para eliminar.");
            }

            $idUsuario = intval($_POST['idUsuario']);
            $resultado = $usuarioDao->eliminarUsuarioConPerfiles($idUsuario); // Cambia este método

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Usuario y perfiles eliminados exitosamente.']);
            } else {
                throw new Exception("Hubo un problema al eliminar el usuario y sus perfiles.");
            }
            exit; // Termina la ejecución después de la eliminación
        } else {
            // Verifica que los datos para actualizar sean numéricos
            if (!is_numeric($_POST['idUsuario']) || !is_numeric($_POST['usuarioModificacion'])) {
                throw new Exception("ID de Usuario y Usuario de Modificación deben ser numéricos.");
            }

            // Lee los datos enviados para la actualización
            $idUsuario = intval($_POST['idUsuario']);
            $dni = $_POST['dni'];
            $nombres = $_POST['nombres'];
            $apellidoPaterno = $_POST['apellidoPaterno'];
            $apellidoMaterno = $_POST['apellidoMaterno'];
            $celular = $_POST['celular'];
            $correoElectronico = $_POST['correoElectronico'];
            $clave = $_POST['clave'];
            $usuarioModificacion = intval($_POST['usuarioModificacion']);
            $fechaNacimiento = $_POST['fechaNacimiento'];
            $estadoRegistro = intval($_POST['estadoRegistro']);

            // Llama al método para actualizar el usuario
            $resultado = $usuarioDao->actualizarUsuario($idUsuario, $dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular, $correoElectronico, $clave, $usuarioModificacion,$fechaNacimiento, $estadoRegistro, $distrito);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente.']);
            } else {
                throw new Exception("Hubo un problema al actualizar el usuario.");
            }
        }
    }
} catch (Exception $e) {
    error_log("Error en la operación de usuario: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
