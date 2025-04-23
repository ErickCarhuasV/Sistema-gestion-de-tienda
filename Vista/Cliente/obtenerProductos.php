<?php

header('Content-Type: application/json');

// Obtener los filtros
$areaId = $_GET['areaId'] ?? '';
$seccionId = $_GET['seccionId'] ?? '';
$lineaId = $_GET['lineaId'] ?? '';
$familiaId = $_GET['familiaId'] ?? '';
$subfamiliaId = $_GET['subfamiliaId'] ?? '';

// Lógica para filtrar productos
$productos = []; // Aquí incluirás la lógica para obtener los productos desde la base de datos
// Ejemplo: $productos = $db->getProductosFiltrados($areaId, $seccionId, $lineaId, $familiaId, $subfamiliaId);

// Devuelve los productos en formato JSON
echo json_encode($productos);
// Configura los datos de conexión a la base de datos
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "tu_base_de_datos";

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión es exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los productos
$sql = "SELECT id, nombre, precio, descuento, imagen, envio FROM productos";
$result = $conn->query($sql);

// Array para almacenar los datos de los productos
$productos = array();

if ($result->num_rows > 0) {
    // Recorrer cada fila y agregarla al array
    while($row = $result->fetch_assoc()) {
        $productos[] = array(
            "id" => $row["id"],
            "nombre" => $row["nombre"],
            "precio" => $row["precio"],
            "descuento" => $row["descuento"],
            "imagen" => $row["imagen"],       // URL o ruta de la imagen del producto
            "envio" => $row["envio"] ? "Envío gratis" : "Sin envío gratis"
        );
    }
}

// Cerrar la conexión
$conn->close();

// Enviar los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($productos);
?>
