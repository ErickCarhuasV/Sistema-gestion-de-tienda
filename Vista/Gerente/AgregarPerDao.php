<?php
class AgregarPerDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function agregarPerfil($perfil) {
        try {
            $sql = "INSERT INTO USUARIO_PERFILES (IDUSUARIO, IDPERFIL, ESTADOREGISTRO) 
                    VALUES (:idUsuario, :idPerfil, :estadoRegistro)";
            $stmt = $this->conexion->prepare($sql);

            // Asigna los valores a los parámetros
            $stmt->bindValue(':idUsuario', $perfil->getIdUsuario());
            $stmt->bindValue(':idPerfil', $perfil->getIdPerfil());
            $stmt->bindValue(':estadoRegistro', $perfil->getEstadoRegistro());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al agregar perfil: " . $e->getMessage());
        }
    }
}
?>
