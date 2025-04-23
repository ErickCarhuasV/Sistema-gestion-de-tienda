<?php

class UsuarioBean {
    private $idUsuario;
    private $dni;
    private $nombres;
    private $apellidoPaterno;
    private $apellidoMaterno;
    private $celular;
    private $correoElectronico;
    private $clave; // La clave se maneja solo para edición.
    private $usuarioCreacion;
    private $fechaCreacion;
    private $usuarioModificacion;
    private $fechaModificacion;
    private $estadoRegistro;
    private $sexo;
    private $fechaNacimiento;
    private $idDireccion;
    private $direccion;




    // Constructor
    public function __construct($data) {
        $this->idUsuario = $data['IDUSUARIO'] ?? null; // Este campo es relevante para la edición.
        $this->dni = $data['DNI'] ?? null;
        $this->nombres = $data['NOMBRES'] ?? null;
        $this->apellidoPaterno = $data['APELLIDOPATERNO'] ?? null;
        $this->apellidoMaterno = $data['APELLIDOMATERNO'] ?? null;
        $this->celular = $data['CELULAR'] ?? null;
        $this->correoElectronico = $data['CORREOELECTRONICO'] ?? null;
        $this->clave = $data['CLAVE'] ?? null; // Puede ser null si no se cambia.
        $this->usuarioCreacion = $data['USUARIOCREACION'] ?? null;
        $this->fechaCreacion = $data['FECHACREACION'] ?? null;
        $this->idFechaNacimiento = $data['ID_FECHA_NACIMIENTO'] ?? null; // Guardamos el ID
        $this->fechaNacimiento = $data['FECHA_NACIMIENTO'] ?? null;
        $this->sexo = $data['sexo'] ?? null;
        $this->usuarioModificacion = $data['USUARIOMODIFICACION'] ?? null;
        $this->fechaModificacion = $data['FECHAMODIFICACION'] ?? null;
        $this->estadoRegistro = $data['ESTADOREGISTRO'] ?? null;
        $this->idDireccion = $data['ID_DIRECCION'] ?? null;
        $this->direccion = $data['DIRECCION'] ?? null;
    }

    public function getIdDireccion() {
        return $this->idDireccion;
    }

    public function setIdDireccion($idDireccion) {
        $this->idDireccion = $idDireccion;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
    // Getters
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getDni() {
        return $this->dni;
    }

    public function getNombres() {
        return $this->nombres;
    }

    public function getApellidoPaterno() {
        return $this->apellidoPaterno;
    }

    public function getApellidoMaterno() {
        return $this->apellidoMaterno;
    }

    public function getCelular() {
        return $this->celular;
    }

    public function getCorreoElectronico() {
        return $this->correoElectronico;
    }

    public function getClave() {
        return $this->clave; // Esta clave es opcional al editar
    }

    public function getUsuarioCreacion() {
        return $this->usuarioCreacion;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    public function getIdFechaNacimiento() {
        return $this->idFechaNacimiento;
    }
    public function getUsuarioModificacion() {
        return $this->usuarioModificacion;
    }

    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    public function getEstadoRegistro() {
        return $this->estadoRegistro;
    }


    // Setters
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setDni($dni) {
        $this->dni = $dni;
    }

    public function setNombres($nombres) {
        $this->nombres = $nombres;
    }

    public function setApellidoPaterno($apellidoPaterno) {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    public function setApellidoMaterno($apellidoMaterno) {
        $this->apellidoMaterno = $apellidoMaterno;
    }

    public function setCelular($celular) {
        $this->celular = $celular;
    }

    public function setCorreoElectronico($correoElectronico) {
        $this->correoElectronico = $correoElectronico;
    }

    public function setClave($clave) {
        $this->clave = $clave; // Esta se usará para editar, pero puede ser null si no se cambia.
    }

    public function setUsuarioCreacion($usuarioCreacion) {
        $this->usuarioCreacion = $usuarioCreacion;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function setUsuarioModificacion($usuarioModificacion) {
        $this->usuarioModificacion = $usuarioModificacion;
    }

    public function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    public function setEstadoRegistro($estadoRegistro) {
        $this->estadoRegistro = $estadoRegistro;
    }
    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getSexo() {
        return $this->sexo;
    }

}
?>
