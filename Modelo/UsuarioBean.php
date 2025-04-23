<?php
class UsuarioBean {
    private $correoElectronico;
    private $clave;
    private $idPerfil; 
    private $sexo;
    private $fechaNacimiento;

    public function __construct($correoElectronico, $clave, $idPerfil = null) {
        $this->correoElectronico = $correoElectronico;
        $this->clave = $clave;
        $this->idPerfil = $idPerfil; 
        $this->fechaNacimiento = $fechaNacimiento;
        $this->sexo = $sexo;
    }

    public function getCorreoElectronico() {
        return $this->correoElectronico;
    }
    public function setCorreoElectronico($correoElectronico) {
        $this->correoElectronico = $correoElectronico;
    }

    public function getClave() {
        return $this->clave;
    }

    public function getIdPerfil() {
        return $this->idPerfil;
    }

    public function setIdPerfil($idPerfil) {
        $this->idPerfil = $idPerfil;
    }
    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getSexo() {
        return $this->sexo;
    }
    
    public function getIdFechaNacimiento() {
        return $this->idFechaNacimiento;
    }
    public function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }
    

}
?>