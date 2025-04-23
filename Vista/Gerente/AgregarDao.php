<?php
class AgregarDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function agregarUsuario($usuario, $direccion, $estadoRegistro) {
        try {
            // Validar formato de fecha de nacimiento
            $fecha = $usuario->getFechaNacimiento();
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                throw new Exception("El formato de fecha es inválido. Debe ser YYYY-MM-DD.");
            }

            list($anio, $mes, $dia) = explode('-', $fecha);

            // Iniciar transacción
            $this->conexion->beginTransaction();

            // Buscar IDs de día, mes, año
            $idDia = $this->obtenerId("DIA", "ID_DIA", intval($dia));
            $idMes = $this->obtenerId("MES", "ID_MES", intval($mes));
            $idAnio = $this->obtenerId("ANIO", "DESCRIPCION", intval($anio));

            if (!$idDia || !$idMes || !$idAnio) {
                throw new Exception("No se encontraron registros para el día, mes o año proporcionados.");
            }

            // Insertar fecha de nacimiento
            $sqlFecha = "INSERT INTO FECHA_NACIMIENTO (ID, ID_DIA, ID_MES, ID_ANIO) VALUES (FECHA_SEQ.NEXTVAL, ?, ?, ?)";
            $stmtFecha = $this->conexion->prepare($sqlFecha);
            $stmtFecha->execute([$idDia, $idMes, $idAnio]);

            $idFechaNacimiento = $this->conexion->query("SELECT FECHA_SEQ.CURRVAL FROM dual")->fetchColumn();

            // Manejo de dirección
            $idDireccion = $this->insertarDireccion($direccion);

            // Insertar usuario
            $sqlUsuario = "INSERT INTO USUARIO 
                (IDUSUARIO, DNI, NOMBRES, APELLIDOPATERNO, APELLIDOMATERNO, CELULAR, CORREOELECTRONICO, CLAVE, ESTADOREGISTRO, ID_FECHA_NACIMIENTO, ID_SEXO, ID_DIRECCION) 
                VALUES (USUARIO_SEQ.NEXTVAL, :dni, :nombres, :apellidoPaterno, :apellidoMaterno, :celular, :correoElectronico, :clave, :estadoRegistro, :idFechaNacimiento, :sexo, :direccion)";
            
            $stmtUsuario = $this->conexion->prepare($sqlUsuario);
            $stmtUsuario->execute([
                ':dni' => $usuario->getDni(),
                ':nombres' => $usuario->getNombres(),
                ':apellidoPaterno' => $usuario->getApellidoPaterno(),
                ':apellidoMaterno' => $usuario->getApellidoMaterno(),
                ':celular' => $usuario->getCelular(),
                ':correoElectronico' => $usuario->getCorreoElectronico(),
                ':clave' => $usuario->getClave(),
                ':estadoRegistro' => $estadoRegistro,
                ':idFechaNacimiento' => $idFechaNacimiento,
                ':sexo' => $usuario->getSexo(),
                ':direccion' => $idDireccion
            ]);

            // Insertar perfil de usuario
            $sqlPerfil = "INSERT INTO USUARIO_PERFILES (IDUSUARIO, IDPERFIL, ESTADOREGISTRO) VALUES (USUARIO_SEQ.CURRVAL, 3, 1)";
            $this->conexion->exec($sqlPerfil);

            // Confirmar transacción
            $this->conexion->commit();
            return true;

        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw new Exception("Error al agregar usuario: " . $e->getMessage());
        }
    }

    private function obtenerId($tabla, $campo, $valor) {
        $sql = "SELECT $campo FROM $tabla WHERE $campo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$valor]);
        return $stmt->fetchColumn();
    }

    private function insertarDireccion($direccion) {
        $sqlDistrito = "SELECT ID_DISTRITO FROM DISTRITO WHERE NOMBRE_DISTRITO = ?";
        $sqlProvincia = "SELECT ID_PROVINCIA FROM PROVINCIA WHERE NOMBRE_PROVINCIA = ?";
        $sqlRegion = "SELECT ID_REGION FROM REGION WHERE NOMBRE_REGION = ?";

        $stmtDistrito = $this->conexion->prepare($sqlDistrito);
        $stmtDistrito->execute([$direccion->getDistrito()]);
        $idDistrito = $stmtDistrito->fetchColumn();

        $stmtProvincia = $this->conexion->prepare($sqlProvincia);
        $stmtProvincia->execute([$direccion->getProvincia()]);
        $idProvincia = $stmtProvincia->fetchColumn();

        $stmtRegion = $this->conexion->prepare($sqlRegion);
        $stmtRegion->execute([$direccion->getRegion()]);
        $idRegion = $stmtRegion->fetchColumn();

        if (!$idDistrito || !$idProvincia || !$idRegion) {
            throw new Exception("No se encontraron registros para la dirección proporcionada.");
        }

        $sqlDireccion = "INSERT INTO DIRECCION (ID_DIRECCION, ID_DISTRITO, ID_PROVINCIA, ID_REGION, DIRECCION) 
                         VALUES (DIRECCION_SEQ.NEXTVAL, ?, ?, ?, ?)";
        $stmtDireccion = $this->conexion->prepare($sqlDireccion);
        $stmtDireccion->execute([$idDistrito, $idProvincia, $idRegion, $direccion->getDireccionCompleta()]);

        return $this->conexion->query("SELECT DIRECCION_SEQ.CURRVAL FROM dual")->fetchColumn();
    }
}
?>
