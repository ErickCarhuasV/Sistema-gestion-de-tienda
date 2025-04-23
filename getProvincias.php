<?php
require_once 'Conexion/bd.php';

// Verificar si el parámetro 'region' está presente y es válido
if (!isset($_GET['regionId']) || !is_numeric($_GET['regionId'])) {
    echo json_encode(['error' => 'Parámetro de región no válido o ausente']);
    exit;
}

$regionId = $_GET['regionId'];

try {
    // Crear una instancia de la clase ConexionBD para obtener la conexión
    $conexionBD = new ConexionBD();
    $conn = $conexionBD->getConexionBD();

    // Preparar la consulta SQL
    $sql = "SELECT ID_PROVINCIA, NOMBRE_PROVINCIA FROM PROVINCIA WHERE ID_REGION = :regionId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':regionId', $regionId, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();

    // Verificar si se encontraron provincias
    $provincias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($provincias) {
        echo json_encode($provincias);  // Devolver los resultados en formato JSON
    } else {
        echo json_encode(['message' => 'No se encontraron provincias para esta región']);
    }
} catch (PDOException $e) {
    // Manejo de errores en caso de fallo en la base de datos
    echo json_encode(['error' => 'Error al conectar o consultar la base de datos', 'details' => $e->getMessage()]);
}
?>
