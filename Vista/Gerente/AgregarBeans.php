<?php

// Clase principal para manejo de usuarios
class Usuario {
    private $dni;
    private $nombres;
    private $apellidoPaterno;
    private $apellidoMaterno;
    private $celular;
    private $correoElectronico;
    private $clave;
    private $fechaNacimiento;
    private $sexo;
    private $direccion;
    private $regionId;    // Nueva propiedad
    private $provinciaId; // Nueva propiedad
    private $distritoId;  // Nueva propiedad

    // Constructor
    public function __construct(array $data = []) {
        $this->dni = $data['dni'] ?? null;
        $this->nombres = $data['nombres'] ?? null;
        $this->apellidoPaterno = $data['apellidoPaterno'] ?? null;
        $this->apellidoMaterno = $data['apellidoMaterno'] ?? null;
        $this->celular = $data['celular'] ?? null;
        $this->correoElectronico = $data['correoElectronico'] ?? null;
        $this->clave = $data['clave'] ?? null;
        $this->fechaNacimiento = $data['fechaNacimiento'] ?? null;
        $this->sexo = $data['sexo'] ?? null;
        $this->direccion = $data['direccion'] ?? null;
        $this->regionId = $data['regionId'] ?? null;       // Inicialización
        $this->provinciaId = $data['provinciaId'] ?? null; // Inicialización
        $this->distritoId = $data['distritoId'] ?? null;   // Inicialización
    }

    // Getters y Setters
    public function getDni(): ?string {
        return $this->dni;
    }

    public function setDni(string $dni): void {
        $this->dni = $dni;
    }

    public function getNombres(): ?string {
        return $this->nombres;
    }

    public function setNombres(string $nombres): void {
        $this->nombres = $nombres;
    }

    // Métodos similares para las demás propiedades
    public function getApellidoPaterno(): ?string {
        return $this->apellidoPaterno;
    }

    public function setApellidoPaterno(string $apellidoPaterno): void {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    public function getApellidoMaterno(): ?string {
        return $this->apellidoMaterno;
    }

    public function setApellidoMaterno(string $apellidoMaterno): void {
        $this->apellidoMaterno = $apellidoMaterno;
    }

    public function getCelular(): ?string {
        return $this->celular;
    }

    public function setCelular(string $celular): void {
        $this->celular = $celular;
    }

    public function getCorreoElectronico(): ?string {
        return $this->correoElectronico;
    }

    public function setCorreoElectronico(string $correoElectronico): void {
        $this->correoElectronico = $correoElectronico;
    }

    public function getClave(): ?string {
        return $this->clave;
    }

    public function setClave(string $clave): void {
        $this->clave = $clave;
    }

    public function getFechaNacimiento(): ?string {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(string $fechaNacimiento): void {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getSexo(): ?string {
        return $this->sexo;
    }

    public function setSexo(string $sexo): void {
        $this->sexo = $sexo;
    }

    public function getDireccion(): ?string {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): void {
        $this->direccion = $direccion;
    }

    // Getters y setters para las nuevas propiedades
    public function getRegionId(): ?int {
        return $this->regionId;
    }

    public function setRegionId(int $regionId): void {
        $this->regionId = $regionId;
    }

    public function getProvinciaId(): ?int {
        return $this->provinciaId;
    }

    public function setProvinciaId(int $provinciaId): void {
        $this->provinciaId = $provinciaId;
    }

    public function getDistritoId(): ?int {
        return $this->distritoId;
    }

    public function setDistritoId(int $distritoId): void {
        $this->distritoId = $distritoId;
    }
}

?>
