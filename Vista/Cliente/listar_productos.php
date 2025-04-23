<?php
// Iniciar la captura de salida
ob_start();

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
} else {
    // Si no hay productos, enviar mensaje vacío
    $productos = array('error' => 'No se encontraron productos');
}

// Cerrar la conexión
$conn->close();

// Enviar los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($productos);

// Finalizar la captura de salida (en caso de usar ob_start)
ob_end_flush();
?>

