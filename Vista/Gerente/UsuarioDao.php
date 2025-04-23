    <?php
    require_once '../../Conexion/bd.php'; // Asegúrate de incluir el archivo de conexión
    require_once 'UsuarioBean.php'; // Asegúrate de incluir el archivo de UsuarioBean

    class UsuarioDao {
        private $conexion;

        public function __construct($conexion) {
            $this->conexion = $conexion; // Se utiliza la conexión pasada
        }

        public function obtenerUsuarios() {
            $usuarios = [];
            try {
                $sql = " SELECT 
                        u.IDUSUARIO, 
                        u.DNI, 
                        u.NOMBRES, 
                        u.APELLIDOPATERNO, 
                        u.APELLIDOMATERNO, 
                        u.CELULAR, 
                        u.CORREOELECTRONICO, 
                        u.USUARIOCREACION, 
                        u.FECHACREACION, 
                        u.USUARIOMODIFICACION, 
                        u.FECHAMODIFICACION, 
                        u.ESTADOREGISTRO, 
                        u.ID_SEXO,
                        u.ID_DIRECCION, -- Incluye el ID de la dirección.
                        d.DIRECCION, -- Valor concatenado.
                        fn.FECHA_COMPLETA AS FECHA_NACIMIENTO
                FROM USUARIO u  
                LEFT JOIN DIRECCION d ON u.ID_DIRECCION = d.ID_DIRECCION
                LEFT JOIN FECHA_NACIMIENTO fn ON u.ID_FECHA_NACIMIENTO = fn.ID";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
                // Crea instancias de UsuarioBean con los datos obtenidos
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $usuarios[] = new UsuarioBean($row);
                }
            } catch (PDOException $e) {
                error_log("Error en la consulta: " . $e->getMessage());
                // Manejo de errores adicional
            }

            return $usuarios;
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
                        USUARIOMODIFICACION = :usuarioModificacion,
                        FECHAMODIFICACION = CURRENT_TIMESTAMP
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

        public function eliminarUsuarioConPerfiles($idUsuario) {
            try {
                // Iniciar una transacción
                $this->conexion->beginTransaction();

                // Primero eliminamos los perfiles asociados
                $sqlPerfiles = "DELETE FROM USUARIO_PERFILES WHERE IDUSUARIO = :idUsuario";
                $stmtPerfiles = $this->conexion->prepare($sqlPerfiles);
                $stmtPerfiles->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $stmtPerfiles->execute();

                // Luego eliminamos el usuario
                $sqlUsuario = "DELETE FROM USUARIO WHERE IDUSUARIO = :idUsuario";
                $stmtUsuario = $this->conexion->prepare($sqlUsuario);
                $stmtUsuario->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $stmtUsuario->execute();

                // Si todo fue bien, confirmar la transacción
                $this->conexion->commit();
                return true;
            } catch (Exception $e) {
                // Revertir la transacción en caso de error
                $this->conexion->rollBack();
                error_log("Error al eliminar usuario y perfiles: " . $e->getMessage());
                return false;
            }
        }
    }
    ?>