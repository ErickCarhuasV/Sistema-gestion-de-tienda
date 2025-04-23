<?php
class AgregarPerBeans {
    private $idUsuario;
    private $idPerfil;
    private $estadoRegistro;

    public function __construct($data) {
        $this->idUsuario = $data['idUsuario'] ?? null; // IDUSUARIO se asignará al crear el usuario
        $this->idPerfil = $data['idPerfil'] ?? null; // IDPERFIL (2 o 3)
        $this->estadoRegistro = 1; // Se establece por defecto en 1
    }

    // Getters
    public function getIdUsuario() { return $this->idUsuario; }
    public function getIdPerfil() { return $this->idPerfil; }
    public function getEstadoRegistro() { return $this->estadoRegistro; }
}
?>
