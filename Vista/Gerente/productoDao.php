<?php
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
require_once '../../Conexion/bd.php';
require_once 'productoBeans.php';


class AreaDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarAreas() {
        try {
            $sql = "SELECT ID_AREA, DESCRIPCION_AREA FROM AREA";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();

            $areas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $area = new AreaBean();
                $area->setIdArea($row['ID_AREA']);
                $area->setDescripcion($row['DESCRIPCION_AREA']);
                $areas[] = $area;
            }
            return $areas;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function agregarArea($descripcion) {
        try {
            $sql = "INSERT INTO AREA (ID_AREA, DESCRIPCION_AREA) VALUES (SEQ_AREA.NEXTVAL, :descripcion)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':descripcion', $descripcion);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerAreaPorId($idArea) {
        try {
            $sql = "SELECT ID_AREA, DESCRIPCION_AREA FROM AREA WHERE ID_AREA = :idArea";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idArea', $idArea);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $area = new AreaBean();
            $area->setIdArea($row['ID_AREA']);
            $area->setDescripcion($row['DESCRIPCION_AREA']);
            return $area;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function editarArea($idArea, $descripcion) {
        try {
            $sql = "UPDATE AREA SET DESCRIPCION_AREA = :descripcion WHERE ID_AREA = :idArea";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idArea', $idArea);
            $stmt->bindParam(':descripcion', $descripcion);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}

class SeccionDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarSecciones() {
        try {
            $sql = "SELECT ID_SECCION, DESCRIPCION_SECCION, ID_AREA FROM SECCION";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();

            $secciones = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $seccion = new SeccionBean();
                $seccion->setIdSeccion($row['ID_SECCION']);
                $seccion->setDescripcion($row['DESCRIPCION_SECCION']);
                $seccion->setIdArea($row['ID_AREA']);
                $secciones[] = $seccion;
            }
            return $secciones;
        } catch (PDOException $e) {
            // Manejo de errores
            return [];
        }
    }

    public function agregarSeccion($descripcion, $idArea) {
        try {
            // Utilizamos la secuencia SEQ_SECCION para obtener un nuevo ID
            $sql = "INSERT INTO SECCION (ID_SECCION, DESCRIPCION_SECCION, ID_AREA) VALUES (SEQ_SECCION.NEXTVAL, :descripcion, :idArea)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':idArea', $idArea);

            return $stmt->execute(); // Devuelve true si la inserción es exitosa
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            return false; // Indica que hubo un error
        }
    }
    public function obtenerSeccionPorId($idSeccion) {
        try {
            $sql = "SELECT ID_SECCION, DESCRIPCION_SECCION, ID_AREA FROM SECCION WHERE ID_SECCION = :idSeccion";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idSeccion', $idSeccion);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $seccion = new SeccionBean();
            $seccion->setIdSeccion($row['ID_SECCION']);
            $seccion->setDescripcion($row['DESCRIPCION_SECCION']);
            $seccion->setIdArea($row['ID_AREA']);
            return $seccion;
        } catch (PDOException $e) {
            return null;
        }
    }
    

    public function editarSeccion($idSeccion, $descripcion, $idArea) {
        try {
            $sql = "UPDATE SECCION SET DESCRIPCION_SECCION = :descripcion, ID_AREA = :idArea WHERE ID_SECCION = :idSeccion";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idSeccion', $idSeccion);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':idArea', $idArea);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function listarSeccionesPorArea($idArea) {
        $query = "SELECT id_seccion, descripcion FROM secciones WHERE id_area = :idArea"; // Ajusta el nombre de la tabla y columnas
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':idArea', $idArea, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna un array asociativo con los resultados
    }
}



class LineaDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarLineas() {
        try {
            $sql = "
                SELECT 
                    ID_LINEA, 
                    DESCRIPCION_LINEA 
                FROM 
                    LINEA
            ";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();

            $lineas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $linea = new LineaBean(); // Asegúrate de que LineaBean esté definido
                $linea->setIdLinea($row['ID_LINEA']);
                $linea->setDescripcion($row['DESCRIPCION_LINEA']);
                $lineas[] = $linea;
            }
            return $lineas;

        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return [];
        }
    }
    public function obtenerLineaPorId($idLinea) {
        try {
            $sql = "SELECT ID_LINEA, DESCRIPCION_LINEA, ID_SECCION FROM LINEA WHERE ID_LINEA = :idLinea"; // Incluir ID_SECCION
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idLinea', $idLinea, PDO::PARAM_INT);
            $stmt->execute();
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $linea = new LineaBean();
                $linea->setIdLinea($row['ID_LINEA']);
                $linea->setDescripcion($row['DESCRIPCION_LINEA']);
                $linea->setIdSeccion($row['ID_SECCION']); // Asignar ID_SECCION
                return $linea;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error en obtenerLineaPorId: " . $e->getMessage());
            return null;
        }
    }
    
    public function agregarLinea($descripcion, $idSeccion) {
        try {
            $sql = "INSERT INTO LINEA (ID_LINEA, DESCRIPCION_LINEA, ID_SECCION) VALUES (SEQ_LINEA.NEXTVAL, :descripcion, :idSeccion)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':idSeccion', $idSeccion);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al agregar línea: " . $e->getMessage());
            return false;
        }
    }
    
    
    
    public function editarLinea($idLinea, $descripcionLinea, $idSeccion) {
        try {
            $sql = "
                UPDATE LINEA 
                SET DESCRIPCION_LINEA = :descripcionLinea, 
                    ID_SECCION = :idSeccion 
                WHERE ID_LINEA = :idLinea
            ";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':descripcionLinea', $descripcionLinea);
            $stmt->bindParam(':idSeccion', $idSeccion);
            $stmt->bindParam(':idLinea', $idLinea);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al editar línea: " . $e->getMessage());
            return false;
        }
    }
    
    
    
}


class FamiliaDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Función para listar familias
    public function listarFamilias() {
        try {
            $sql = "
                SELECT 
                    ID_FAMILIA, 
                    DESCRIPCION_FAMILIA, 
                    ID_LINEA
                FROM 
                    FAMILIA
            ";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();

            $familias = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $familia = new FamiliaBean(); // Asegúrate de que FamiliaBean esté definido
                $familia->setIdFamilia($row['ID_FAMILIA']);
                $familia->setDescripcion($row['DESCRIPCION_FAMILIA']);
                $familia->setIdLinea($row['ID_LINEA']); // Agrega ID_LINEA
                $familias[] = $familia;
            }
            return $familias;

        } catch (PDOException $e) {
            error_log("Error en listarFamilias: " . $e->getMessage());
            return [];
        }
    }

    // Función para obtener una familia por su ID
    public function obtenerFamiliaPorId($idFamilia) {
        try {
            $sql = "
                SELECT 
                    ID_FAMILIA, 
                    DESCRIPCION_FAMILIA, 
                    ID_LINEA
                FROM 
                    FAMILIA 
                WHERE 
                    ID_FAMILIA = :idFamilia
            ";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':idFamilia', $idFamilia, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $familia = new FamiliaBean();
                $familia->setIdFamilia($row['ID_FAMILIA']);
                $familia->setDescripcion($row['DESCRIPCION_FAMILIA']);
                $familia->setIdLinea($row['ID_LINEA']); // Asignar ID_LINEA
                return $familia;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error en obtenerFamiliaPorId: " . $e->getMessage());
            return null;
        }
    }

    // Función para agregar una familia (incluye ID_LINEA)
    public function agregarFamilia($descripcion, $idLinea) {
        $sql = "INSERT INTO FAMILIA (ID_FAMILIA, DESCRIPCION_FAMILIA, ID_LINEA) VALUES (SEQ_FAMILIA.NEXTVAL, :descripcion, :idLinea)";
        try {
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idLinea', $idLinea);
        return $stmt->execute();
        } catch (PDOException $e) {
        error_log("Error al agregar línea: " . $e->getMessage());
        return false;
    }
    }

    // Función para editar una familia (incluye ID_LINEA)
    public function editarFamilia($idFamilia, $descripcionFamilia, $idLinea) {
        try {
            $sql = "
                UPDATE FAMILIA 
                SET 
                    DESCRIPCION_FAMILIA = :descripcionFamilia, 
                    ID_LINEA = :idLinea 
                WHERE 
                    ID_FAMILIA = :idFamilia
            ";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':descripcionFamilia', $descripcionFamilia);
            $stmt->bindParam(':idLinea', $idLinea);
            $stmt->bindParam(':idFamilia', $idFamilia);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al editar familia: " . $e->getMessage());
            return false;
        }
    }
}


class SubfamiliaDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarSubfamilias() {
        $sql = "SELECT ID_SUBFAMILIA, DESCRIPCION_SUBFAMILIA, ID_FAMILIA FROM SUBFAMILIA";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $subfamilias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $subfamilia = new SubfamiliaBean();
            $subfamilia->setIdSubfamilia($row['ID_SUBFAMILIA']);
            $subfamilia->setDescripcionSubfamilia($row['DESCRIPCION_SUBFAMILIA']);
            $subfamilia->setIdFamilia($row['ID_FAMILIA']);
            $subfamilias[] = $subfamilia;
        }
        return $subfamilias;
    }

    public function agregarSubfamilia($descripcionSubfamilia, $idFamilia) {
        $sql = "INSERT INTO SUBFAMILIA (ID_SUBFAMILIA, DESCRIPCION_SUBFAMILIA, ID_FAMILIA) VALUES (SEQ_SUBFAMILIA.NEXTVAL, :descripcion, :idFamilia)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcionSubfamilia);
        $stmt->bindParam(':idFamilia', $idFamilia);
        return $stmt->execute();
    }

    public function obtenerSubfamilia($idSubfamilia) {
        $sql = "SELECT ID_SUBFAMILIA, DESCRIPCION_SUBFAMILIA, ID_FAMILIA FROM SUBFAMILIA WHERE ID_SUBFAMILIA = :idSubfamilia";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idSubfamilia', $idSubfamilia);
        $stmt->execute();
    
        // Cambia esto para usar un nuevo objeto SubfamiliaBean
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $subfamilia = new SubfamiliaBean();
            $subfamilia->setIdSubfamilia($row['ID_SUBFAMILIA']);
            $subfamilia->setDescripcionSubfamilia($row['DESCRIPCION_SUBFAMILIA']);
            $subfamilia->setIdFamilia($row['ID_FAMILIA']);
            return $subfamilia;
        }
        
        return null; // Devuelve null si no se encuentra la subfamilia
    }
    


    public function editarSubfamilia($idSubfamilia, $descripcionSubfamilia, $idFamilia) {
        $sql = "UPDATE SUBFAMILIA SET DESCRIPCION_SUBFAMILIA = :descripcion, ID_FAMILIA = :idFamilia WHERE ID_SUBFAMILIA = :idSubfamilia";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcionSubfamilia);
        $stmt->bindParam(':idFamilia', $idFamilia);
        $stmt->bindParam(':idSubfamilia', $idSubfamilia);
    
        if (!$stmt->execute()) {
            // Manejo de errores
            $errorInfo = $stmt->errorInfo();
            echo "Error en la actualización: " . $errorInfo[2]; // Mensaje de error
            return false;
        }
        return true;
    }
    
}

class MarcaDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarMarcas() {
        try {
            $sql = "SELECT ID_MARCA, DESCRIPCION_MARCA FROM MARCA";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();

            $marcas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $marca = new MarcaBean();
                $marca->setIdMarca($row['ID_MARCA']);
                $marca->setDescripcion(mb_convert_encoding($row['DESCRIPCION_MARCA'], 'UTF-8', 'ISO-8859-1'));
                $marcas[] = $marca;
            }
            return $marcas;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return [];
        }
    }

    public function agregarMarca($descripcionMarca) {
        $sql = "INSERT INTO MARCA (ID_MARCA, DESCRIPCION_MARCA) VALUES (SEQ_MARCA.NEXTVAL, :descripcionMarca)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':descripcionMarca', $descripcionMarca);
        $stmt->execute();
    }
    

    public function obtenerMarcaPorId($idMarca) {
        $sql = "SELECT ID_MARCA, DESCRIPCION_MARCA FROM MARCA WHERE ID_MARCA = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idMarca);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $marca = new MarcaBean();
            $marca->setIdMarca($row['ID_MARCA']);
            $marca->setDescripcion($row['DESCRIPCION_MARCA']);
            return $marca;
        }
        return null;
    }

    public function editarMarca($idMarca, $descripcion) {
        $sql = "UPDATE MARCA SET DESCRIPCION_MARCA = :descripcion WHERE ID_MARCA = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':id', $idMarca);
        $stmt->execute();
    }
}


class TipoDao {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarTipos() {
        try {
            $sql = "
                SELECT 
                    ID_TIPO, 
                    DESCRIPCION_TIPO 
                FROM 
                    TIPO
            ";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();

            $tipos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tipo = new TipoBean(); // Asegúrate de que TipoBean esté definido
                $tipo->setIdTipo($row['ID_TIPO']);
                $tipo->setDescripcion($row['DESCRIPCION_TIPO']);
                $tipos[] = $tipo;
            }
            return $tipos;

        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return [];
        }
    }
}

class ProductoDAO {
    private $conexion;

    public function __construct() {
        $conexionBD = new ConexionBD();
        $this->conexion = $conexionBD->getConexionBD(); // Obtener la conexión
    }
    public function agregarProducto($nombre, $descripcion, $precio, $tipo, $marca, $subfamilia, $cantidad,$familia,$linea,$seccion,$area) {
        try {
            $query = "INSERT INTO PRODUCTO (ID_PRODUCTO, NOMBRE, DESCRIPCION, PRECIO, ID_TIPO, ID_MARCA, ID_SUBFAMILIA, CANTIDAD, ID_FAMILIA,ID_LINEA,ID_SECCION,ID_AREA)
                      VALUES (producto_seq.NEXTVAL, :nombre, :descripcion, :precio, :tipo, :marca, :subfamilia, :cantidad,:familia,:linea,:seccion,:area)";
    
            $stmt = $this->conexion->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':subfamilia', $subfamilia);
            $stmt->bindParam(':cantidad', $cantidad); // Agregar cantidad
            $stmt->bindParam(':familia', $familia);
            $stmt->bindParam(':linea', $linea); // Agregar cantidad
            $stmt->bindParam(':seccion', $seccion); // Agregar cantidad
            $stmt->bindParam(':area', $area); // Agregar cantidad

    
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    public function listarProductos() {
        try {
            $sql = "
                SELECT 
                    P.ID_PRODUCTO,
                    P.NOMBRE,
                    P.DESCRIPCION,
                    P.PRECIO,
                    P.CANTIDAD,  -- Agregando cantidad
                    T.DESCRIPCION_TIPO,
                    M.DESCRIPCION_MARCA,
                    SF.DESCRIPCION_SUBFAMILIA,
                    F.DESCRIPCION_FAMILIA,
                    L.DESCRIPCION_LINEA,
                    S.DESCRIPCION_SECCION,
                    A.DESCRIPCION_AREA
                FROM 
                    PRODUCTO P
                JOIN SUBFAMILIA SF ON P.ID_SUBFAMILIA = SF.ID_SUBFAMILIA
                JOIN FAMILIA F ON SF.ID_FAMILIA = F.ID_FAMILIA
                JOIN LINEA L ON F.ID_LINEA = L.ID_LINEA
                JOIN SECCION S ON L.ID_SECCION = S.ID_SECCION
                JOIN AREA A ON S.ID_AREA = A.ID_AREA
                JOIN TIPO T ON P.ID_TIPO = T.ID_TIPO
                JOIN MARCA M ON P.ID_MARCA = M.ID_MARCA
            ";
    
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
    
            $productos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Aquí podrías utilizar un Bean de producto
                $producto = new ProductoBean();
                $producto->setIdProducto($row['ID_PRODUCTO']);
                $producto->setNombre($row['NOMBRE']);
                $producto->setDescripcion($row['DESCRIPCION']);
                $producto->setPrecio($row['PRECIO']);
                $producto->setCantidad($row['CANTIDAD']); // Agregando cantidad
                $producto->setTipo($row['DESCRIPCION_TIPO']);
                $producto->setMarca(mb_convert_encoding($row['DESCRIPCION_MARCA'], 'UTF-8', 'ISO-8859-1'));
                $producto->setSubfamilia($row['DESCRIPCION_SUBFAMILIA']);
                $producto->setFamilia($row['DESCRIPCION_FAMILIA']);
                $producto->setLinea($row['DESCRIPCION_LINEA']);
                $producto->setSeccion($row['DESCRIPCION_SECCION']);
                $producto->setArea($row['DESCRIPCION_AREA']);
                $productos[] = $producto;
            }
            return $productos;
    
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return [];
        }
    }
    public function obtenerProductoPorID($id) {
        // Asegúrate de que el nombre de la tabla y columna estén en mayúsculas
        $query = "SELECT * FROM PRODUCTO WHERE ID_PRODUCTO = :id"; 
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $producto = new ProductoBean();
            $producto->setIdProducto($result['ID_PRODUCTO']);
            $producto->setNombre($result['NOMBRE']);          // Asegúrate de que el campo existe en tu tabla
            $producto->setDescripcion($result['DESCRIPCION']); // Asegúrate de que el campo existe en tu tabla
            $producto->setPrecio($result['PRECIO']);           // Asegúrate de que el campo existe en tu tabla
            $producto->setTipo($result['ID_TIPO']);               // Asegúrate de que el campo existe en tu tabla
            $producto->setMarca($result['ID_MARCA']);             // Asegúrate de que el campo existe en tu tabla
            $producto->setSubfamilia($result['ID_SUBFAMILIA']);   // Asegúrate de que el campo existe en tu tabla
            $producto->setCantidad($result['CANTIDAD']);       // Asegúrate de que el campo existe en tu tabla
            $producto->setCantidad($result['ID_FAMILIA']);       // Asegúrate de que el campo existe en tu tabla
            $producto->setCantidad($result['ID_LINEA']);       // Asegúrate de que el campo existe en tu tabla
            $producto->setCantidad($result['ID_SECCION']);       // Asegúrate de que el campo existe en tu tabla
            $producto->setCantidad($result['ID_AREA']);       // Asegúrate de que el campo existe en tu tabla

            return $producto;
        } else {
            return null;
        }
    }
    public function actualizarProducto($id, $nombre, $descripcion, $precio, $tipo, $marca, $subfamilia, $cantidad, $familia,$linea,$seccion,$area) {
        // Asegúrate de usar el nombre exacto de la tabla y de las columnas en la base de datos
        $query = "UPDATE PRODUCTO 
                  SET NOMBRE = :nombre, 
                      DESCRIPCION = :descripcion, 
                      PRECIO = :precio, 
                      ID_TIPO = :tipo, 
                      ID_MARCA = :marca, 
                      ID_SUBFAMILIA = :subfamilia, 
                      CANTIDAD = :cantidad
                      FAMILIA = :familia
                      LINEA = :linea
                      SECCION = :seccion
                      AREA = :area
                  WHERE ID_PRODUCTO = :id";
    
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR); // O PDO::PARAM_INT si es entero
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':marca', $marca, PDO::PARAM_STR);
        $stmt->bindParam(':subfamilia', $subfamilia, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':familia', $familia, PDO::PARAM_INT);
        $stmt->bindParam(':linea', $linea, PDO::PARAM_INT);
        $stmt->bindParam(':seccion', $seccion, PDO::PARAM_INT);
        $stmt->bindParam(':area', $area, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        return $stmt->execute();
    }
    public function eliminarProductoConDetalles($idProducto) {
        try {
            // Iniciar una transacción
            $this->conexion->beginTransaction();
    

    
            // Luego eliminamos el producto
            $sqlProducto = "DELETE FROM PRODUCTO WHERE ID_PRODUCTO = :idProducto";
            $stmtProducto = $this->conexion->prepare($sqlProducto);
            $stmtProducto->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $stmtProducto->execute();
    
            // Si todo fue bien, confirmar la transacción
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->conexion->rollBack();
            error_log("Error al eliminar producto y detalles: " . $e->getMessage());
            return false;
        }
    }
}
?>
