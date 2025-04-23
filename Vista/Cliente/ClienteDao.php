<?php
require_once '../Conexion/bd.php'; // Asegúrate de incluir la conexión
require_once 'ClienteBean.php'; // Incluir el Bean

class ClienteDao {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para obtener los datos del cliente por su correo electrónico
    public function obtenerDatosClientePorCorreo($correoElectronico) {
        try {
            $sql = "SELECT * FROM Cliente WHERE CORREOELECTRONICO = :correoElectronico";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correoElectronico', $correoElectronico);
            $stmt->execute();

            // Si encuentra un usuario, lo retorna en forma de objeto ClienteBean
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($cliente) {
                $clienteBean = new ClienteBean();
                $clienteBean->setIdCliente($cliente['IDCLIENTE']);
                $clienteBean->setDni($cliente['DNI']);
                $clienteBean->setNombres($cliente['NOMBRES']);
                $clienteBean->setApellidoPaterno($cliente['APELLIDOPATERNO']);
                $clienteBean->setApellidoMaterno($cliente['APELLIDOMATERNO']);
                $clienteBean->setCelular($cliente['CELULAR']);
                $clienteBean->setCorreoElectronico($cliente['CORREOELECTRONICO']);
                $clienteBean->setClave($cliente['CLAVE']);
                return $clienteBean;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Error al obtener los datos del cliente: " . $e->getMessage());
            return null;
        }
    }
}
?>
