<?php
require_once 'bd.php'; // Asegúrate de que la conexión a la base de datos está configurada correctamente

// Consulta para obtener las áreas
$query = "SELECT id_area, descripcion FROM areas"; // Cambia esto por tu consulta real
$result = $conn->query($query);

$areas = [];
while ($row = $result->fetch_assoc()) {
    $areas[] = $row; // Agregar cada fila como un arreglo
}

// Devolver los datos como JSON
echo json_encode($areas);
?>
