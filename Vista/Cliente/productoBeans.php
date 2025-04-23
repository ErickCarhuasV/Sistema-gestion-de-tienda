<?php
class ProductoBean {
    private $idProducto;
    private $nombre;
    private $descripcion;
    private $precio;
    private $tipo;
    private $marca;
    private $cantidad;
    private $subfamilia;
    private $familia;
    private $linea;
    private $seccion;
    private $area;
    private $imagen;
    
    public function getImagen() {
        return $this->imagen;
    }

    // Getters y Setters
    public function getIdProducto() {
        return $this->idProducto;
    }

    public function setIdProducto($idProducto) {
        $this->idProducto = $idProducto;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function setMarca($marca) {
        $this->marca = $marca;
    }

    public function getSubfamilia() {
        return $this->subfamilia;
    }

    public function setSubfamilia($subfamilia) {
        $this->subfamilia = $subfamilia;
    }

    public function getFamilia() {
        return $this->familia;
    }

    public function setFamilia($familia) {
        $this->familia = $familia;
    }

    public function getLinea() {
        return $this->linea;
    }

    public function setLinea($linea) {
        $this->linea = $linea;
    }

    public function getSeccion() {
        return $this->seccion;
    }

    public function setSeccion($seccion) {
        $this->seccion = $seccion;
    }

    public function getArea() {
        return $this->area;
    }

    public function setArea($area) {
        $this->area = $area;
    }
}
?>

<?php


class AreaBean {
    private $idArea;
    private $descripcion;

    public function setIdArea($id) {
        $this->idArea = $id;
    }

    public function getIdArea() {
        return $this->idArea;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }
}

class SeccionBean {
    private $idSeccion;
    private $descripcion;
    private $idArea;

    public function getIdSeccion() {
        return $this->idSeccion;
    }

    public function setIdSeccion($idSeccion) {
        $this->idSeccion = $idSeccion;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    public function setIdArea($id) {
        $this->idArea = $id;
    }

    public function getIdArea() {
        return $this->idArea;
    }
}

class LineaBean {
    private $idLinea;
    private $descripcion;
    private $idSeccion; // Nueva propiedad

    public function getIdLinea() {
        return $this->idLinea;
    }

    public function setIdLinea($idLinea) {
        $this->idLinea = $idLinea;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    // Nuevos métodos para idSeccion
    public function getIdSeccion() {
        return $this->idSeccion;
    }

    public function setIdSeccion($idSeccion) {
        $this->idSeccion = $idSeccion;
    }
}

class FamiliaBean {
    private $idFamilia;
    private $descripcion;
    private $idLinea;
    public function getIdFamilia() {
        return $this->idFamilia;
    }

    public function setIdFamilia($idFamilia) {
        $this->idFamilia = $idFamilia;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    public function getIdLinea() {
        return $this->idLinea;
    }

    public function setIdLinea($idLinea) {
        $this->idLinea = $idLinea;
    }

}
class SubfamiliaBean {
    private $idSubfamilia;
    private $descripcionSubfamilia;
    private $idFamilia;

    // Getters
    public function getIdSubfamilia() {
        return $this->idSubfamilia;
    }

    public function getDescripcionSubfamilia() {
        return $this->descripcionSubfamilia;
    }

    public function getIdFamilia() {
        return $this->idFamilia;
    }

    // Setters
    public function setIdSubfamilia($id) {
        $this->idSubfamilia = $id;
    }

    public function setDescripcionSubfamilia($descripcion) {
        $this->descripcionSubfamilia = $descripcion;
    }

    public function setIdFamilia($idFamilia) {
        $this->idFamilia = $idFamilia;
    }
}



class MarcaBean {
    private $idMarca;
    private $descripcion;

    public function getIdMarca() {
        return $this->idMarca;
    }

    public function setIdMarca($idMarca) {
        $this->idMarca = $idMarca;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
}

class TipoBean {
    private $idTipo;
    private $descripcion;

    public function getIdTipo() {
        return $this->idTipo;
    }

    public function setIdTipo($idTipo) {
        $this->idTipo = $idTipo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
}

?>

