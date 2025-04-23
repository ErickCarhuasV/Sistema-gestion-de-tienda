<?php
require_once __DIR__ . '/../Conexion/bd.php';  // Usar __DIR__ para obtener la ruta correcta


class UsuarioDAO {
    private $conn;

    public function __construct($conn) { 
        $this->conn = $conn; 
    }

    public function autenticar(UsuarioBean $usuario) {
        try {
            // Recuperar el hash almacenado en la base de datos
            $sql = "SELECT Clave FROM Usuario WHERE CORREOELECTRONICO = :correoElectronico";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correoElectronico', $usuario->getCorreoElectronico());
            $stmt->execute();
            $hashAlmacenado = $stmt->fetchColumn();
    
            if ($hashAlmacenado && password_verify($usuario->getClave(), $hashAlmacenado)) {
                // Si la verificación del hash es exitosa, la autenticación es válida
                return true;
            } else {
                // Contraseña incorrecta
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error en la autenticación: " . $e->getMessage());
            return false; 
        }
    }
    
    
    public function obtenerPerfil($correoElectronico) {
        try {
            $sql = "SELECT IDPERFIL FROM USUARIO_PERFILES WHERE IDUSUARIO = (SELECT IDUSUARIO FROM USUARIO WHERE CORREOELECTRONICO = :correoElectronico)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':CORREOELECTRONICO', $correoElectronico);
            $stmt->execute();
    
            $idPerfil = $stmt->fetchColumn();
            return $idPerfil;
        } catch (PDOException $e) {
            error_log("Error al obtener el perfil: " . $e->getMessage());
            return null; 
        }
    }

    public function obtenerUsuarioPorDNI($correoElectronico) {
        try {
            $sql = "SELECT IDUSUARIO, NOMBRES, APELLIDOPATERNO, APELLIDOMATERNO, CELULAR, CORREOELECTRONICO FROM Usuario WHERE CORREOELECTRONICO = :correoElectronico"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':CORREOELECTRONICO', $correoElectronico);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Error al obtener el usuario: " . $e->getMessage());
            return null; 
        }
    }
    public function editarUsuario($idUsuario, $dni, $nombre, $apellidoPaterno, $apellidoMaterno, $celular, $correoElectronico, $sexo, $idDireccion, $idFechaNacimiento, $usuarioModificacion) {
        // Consulta SQL para actualizar el usuario
        $sql = "UPDATE USUARIO SET 
                    DNI = :dni,
                    NOMBRES = :nombres,
                    APELLIDOPATERNO = :apellidoPaterno,
                    APELLIDOMATERNO = :apellidoMaterno,
                    CELULAR = :celular,
                    CORREOELECTRONICO = :correoElectronico,
                    ID_SEXO = :sexo,
                    ID_DIRECCION = :idDireccion,
                    ID_FECHA_NACIMIENTO = :idFechaNacimiento,
                WHERE IDUSUARIO = :idUsuario";
        
        $stmt = $this->conn->prepare($sql);
    
        // Vinculación de parámetros
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombres', $nombre);
        $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
        $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':correoElectronico', $correoElectronico);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':idDireccion', $idDireccion);
        $stmt->bindParam(':idFechaNacimiento', $idFechaNacimiento);
        $stmt->bindParam(':idUsuario', $idUsuario);
    
        try {
            // Ejecutar la consulta
            if ($stmt->execute()) {
                return true; // Actualización exitosa
            } else {
                // Captura errores de ejecución
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al ejecutar la consulta: ' . implode(", ", $stmt->errorInfo())
                ]);
                return false;
            }
        } catch (PDOException $e) {
            // Captura errores específicos de base de datos
            echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
            return false;
        } catch (Exception $e) {
            // Captura cualquier otra excepción
            echo json_encode(['success' => false, 'message' => 'Excepción capturada: ' . $e->getMessage()]);
            return false;
        }
    }
    public function obtenerFechaNacimiento($idFecha) {
        try {
            $sql = "SELECT fecha_concatenada FROM FECHA_NACIMIENTO WHERE id = :id";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id', $idFecha);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al obtener fecha: " . $e->getMessage());
            return null;
        }
    }
    public function actualizarUsuario($idUsuario, $dni, $nombres, $apellidoPaterno, $apellidoMaterno, $celular, $correoElectronico, $clave, $usuarioModificacion, $sexo, $estadoRegistro, $fechaNacimiento, $direccion) {
        try {
            $sql = "UPDATE USUARIO SET 
                        DNI = :dni, 
                        NOMBRES = :nombres, 
                        APELLIDOPATERNO = :apellidoPaterno, 
                        APELLIDOMATERNO = :apellidoMaterno, 
                        CELULAR = :celular, 
                        CORREOELECTRONICO = :correoElectronico, 
                        CLAVE = :clave, 
                        DIRECCION = :direccion, 
                        USUARIOMODIFICACION = :usuarioModificacion, 
                        ID_SEXO = :sexo, 
                        ID_FECHA_NACIMIENTO = :fechaNacimiento, 
                        ESTADOREGISTRO = :estadoRegistro 
                    WHERE IDUSUARIO = :idUsuario";
            $stmt = $this->conexion->prepare($sql);

            // Asigna los parámetros
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':apellidoPaterno', $apellidoPaterno);
            $stmt->bindParam(':apellidoMaterno', $apellidoMaterno);
            $stmt->bindParam(':celular', $celular);
            $stmt->bindParam(':correoElectronico', $correoElectronico);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':direccion', $direccion); // Nuevo campo
            $stmt->bindParam(':sexo', $sexo);
            $stmt->bindParam(':usuarioModificacion', $usuarioModificacion);
            $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
            $stmt->bindParam(':estadoRegistro', $estadoRegistro);
            $stmt->bindParam(':idUsuario', $idUsuario);

            return $stmt->execute(); // Devuelve true si la actualización fue exitosa
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        }
    }
    
    
}
?>
