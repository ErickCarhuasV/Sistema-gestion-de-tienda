<?php
require_once 'Conexion/bd.php';

// Verificar si el parámetro 'provincia' está presente y es válido
if (!isset($_GET['provinciaId']) || !is_numeric($_GET['provinciaId'])) {
    echo json_encode(['error' => 'Parámetro de provincia no válido o ausente']);
    exit;
}

$provinciaId = $_GET['provinciaId'];

try {
    // Crear una instancia de la clase ConexionBD para obtener la conexión
    $conexionBD = new ConexionBD();
    $conn = $conexionBD->getConexionBD();

    // Preparar la consulta SQL
    $sql = "SELECT ID_DISTRITO, NOMBRE_DISTRITO FROM DISTRITO WHERE ID_PROVINCIA = :provinciaId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':provinciaId', $provinciaId, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();

    // Verificar si se encontraron distritos
    $distritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($distritos) {
        echo json_encode($distritos);  // Devolver los resultados en formato JSON
    } else {
        echo json_encode(['message' => 'No se encontraron distritos para esta provincia']);
    }
} catch (PDOException $e) {
    // Manejo de errores en caso de fallo en la base de datos
    echo json_encode(['error' => 'Error al conectar o consultar la base de datos', 'details' => $e->getMessage()]);
}
?>
