<?php
class RegistroBean {
    private $idUsuario;
    private $dni;
    private $nombres;
    private $apellidoPaterno;
    private $apellidoMaterno;
    private $celular;
    private $correoElectronico;
    private $clave;
    private $estadoRegistro;
    private $fechaNacimiento;
    private $sexo;
    private $regionId;
    private $provinciaId;
    private $distritoId;
    private $idDireccion;

    public function getIdDireccion() {
        return $this->idDireccion;
    }

    public function setIdDireccion($idDireccion) {
        $this->idUsuario = $idDireccion;
    }
   
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getDni() {
        return $this->dni;
    }

    public function setDni($dni) {
        $this->dni = $dni;
    }

    public function getNombres() {
        return $this->nombres;
    }

    public function setNombres($nombres) {
        $this->nombres = $nombres;
    }

    public function getApellidoPaterno() {
        return $this->apellidoPaterno;
    }

    public function setApellidoPaterno($apellidoPaterno) {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    public function getApellidoMaterno() {
        return $this->apellidoMaterno;
    }

    public function setApellidoMaterno($apellidoMaterno) {
        $this->apellidoMaterno = $apellidoMaterno;
    }

    public function getCelular() {
        return $this->celular;
    }

    public function setCelular($celular) {
        $this->celular = $celular;
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

    public function setClave($clave) {
        $this->clave = $clave;
    }

    public function getEstadoRegistro() {
        return $this->estadoRegistro;
    }

    public function setEstadoRegistro($estadoRegistro) {
        $this->estadoRegistro = $estadoRegistro;
    }
    public function setFechaNacimiento($fecha) {
        $this->fechaNacimiento = $fecha;
    }
    
    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }
    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getSexo() {
        return $this->sexo;
    }
    public function getRegion() {
        return $this->regionId;
    }

    public function setRegion($regionId) {
        $this->regionId = $regionId;
    }

    // Getter y Setter para $provincia
    public function getProvincia() {
        return $this->provinciaId;
    }

    public function setProvincia($provinciaId) {
        $this->provinciaId = $provinciaId;
    }

    // Getter y Setter para $distrito
    public function getDistrito() {
        return $this->distritoId;
    }

    public function setDistrito($distritoId) {
        $this->distritoId = $distritoId;
    }
}
?>
