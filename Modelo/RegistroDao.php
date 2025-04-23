<?php
require_once '../Conexion/bd.php'; 



class RegistroDao {
    private $conn;

    public function __construct($conn) { 
        $this->conn = $conn; 
    }
     
    public function registrarUsuario(RegistroBean $usuario) {
        try {
            // Iniciar transacción
            $this->conn->beginTransaction();
    
            // Obtener los datos del usuario desde el objeto $usuario
            $dni = $usuario->getDni();
            $nombres = $usuario->getNombres();
            $apellidoPaterno = $usuario->getApellidoPaterno();
            $apellidoMaterno = $usuario->getApellidoMaterno();
            $celular = $usuario->getCelular();
            $correo = $usuario->getCorreoElectronico();
            $clave = $usuario->getClave();
            $sexo = $usuario->getSexo();
            $fecha = $usuario->getFechaNacimiento();
            $regionId = $usuario->getRegion();
            $provinciaId = $usuario->getProvincia();
            $distritoId = $usuario->getDistrito();

            // Procesar la fecha de nacimiento
            list($anio, $mes, $dia) = explode('-', $fecha);

            $sqlDia = "SELECT ID_DIA FROM DIA WHERE ID_DIA = ?";
            $stmtDia = $this->conn->prepare($sqlDia);
            $stmtDia->execute([intval($dia)]);
            $idDia = $stmtDia->fetchColumn();
            
            $sqlMes = "SELECT ID_MES FROM MES WHERE ID_MES = ?";
            $stmtMes = $this->conn->prepare($sqlMes);
            $stmtMes->execute([intval($mes)]);
            $idMes = $stmtMes->fetchColumn();
            
            $sqlAnio = "SELECT ID_ANIO FROM ANIO WHERE DESCRIPCION = ?";
            $stmtAnio = $this->conn->prepare($sqlAnio);
            $stmtAnio->execute([intval($anio)]);
            $idAnio = $stmtAnio->fetchColumn();

            // Inserta en la tabla FECHA_NACIMIENTO
            $sqlFecha = "INSERT INTO FECHA_NACIMIENTO (ID, ID_DIA, ID_MES, ID_ANIO) 
                         VALUES (FECHA_SEQ.NEXTVAL, ?, ?, ?)";
            $stmtFecha = $this->conn->prepare($sqlFecha);
            $stmtFecha->execute([$idDia, $idMes, $idAnio]);

            // Recuperar el ID de la fecha generada
            $fechaId = $this->conn->query("SELECT FECHA_SEQ.CURRVAL FROM dual")->fetchColumn();

            // Inserta en la tabla DIRECCION
            $sqlDireccion = "INSERT INTO DIRECCION (ID_DIRECCION, ID_REGION, ID_PROVINCIA, ID_DISTRITO, DIRECCION) 
                             VALUES (DIRECCION_SEQ.NEXTVAL, ?, ?, ?, NULL)";
            $stmtDireccion = $this->conn->prepare($sqlDireccion);
            $stmtDireccion->execute([
                $regionId, $provinciaId, $distritoId
            ]);
            // Recuperar el ID de la dirección generada
            $direccionId = $this->conn->query("SELECT DIRECCION_SEQ.CURRVAL FROM dual")->fetchColumn();

            // Insertar usuario
            $sqlUsuario = "INSERT INTO Usuario (
                IDUSUARIO, DNI, Nombres, ApellidoPaterno, ApellidoMaterno, 
                Celular, CorreoElectronico, Clave, EstadoRegistro, ID_FECHA_NACIMIENTO, ID_SEXO, ID_DIRECCION
            ) VALUES (
                USUARIO_SEQ.NEXTVAL, :dni, :nombres, :apellidoPaterno, :apellidoMaterno,
                :celular, :correo, :clave, 1, :fechaId, :sexo, :direccionId
            )";
            
            $stmtUsuario = $this->conn->prepare($sqlUsuario);
            $stmtUsuario->bindValue(':dni', $dni);
            $stmtUsuario->bindValue(':nombres', $nombres);
            $stmtUsuario->bindValue(':apellidoPaterno', $apellidoPaterno);
            $stmtUsuario->bindValue(':apellidoMaterno', $apellidoMaterno);
            $stmtUsuario->bindValue(':celular', $celular);
            $stmtUsuario->bindValue(':correo', $correo);
            $stmtUsuario->bindValue(':clave', password_hash($clave, PASSWORD_DEFAULT));
            $stmtUsuario->bindValue(':fechaId', $fechaId);
            $stmtUsuario->bindValue(':sexo', $sexo);
            $stmtUsuario->bindValue(':direccionId', $direccionId);
            $stmtUsuario->execute();

            // Insertar perfil de usuario
            $sqlPerfil = "INSERT INTO USUARIO_PERFILES (IDUSUARIO, IDPERFIL, ESTADOREGISTRO) 
                         VALUES (USUARIO_SEQ.CURRVAL, 3, 1)";
            $this->conn->exec($sqlPerfil);

            // Confirmar transacción
            $this->conn->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
?>
