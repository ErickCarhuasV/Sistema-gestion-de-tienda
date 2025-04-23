<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

// Crear archivo de log
$logFile = __DIR__ . '/debug_' . date('Y-m-d') . '.log';

try {
    // Validar parámetros
    if (empty($_GET['tipo']) || empty($_GET['id'])) {
        throw new Exception("Parámetros requeridos faltantes");
    }

    $tipo = trim($_GET['tipo']);
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id === false) {
        throw new Exception("El parámetro 'id' debe ser un número válido.");
    }

    // Registrar la solicitud
    file_put_contents($logFile, sprintf("[%s] Nueva solicitud\n", date('Y-m-d H:i:s')), FILE_APPEND);
    file_put_contents($logFile, sprintf("Tipo: %s, ID: %d\n", $tipo, $id), FILE_APPEND);

    require_once '../../Conexion/bd.php';
    $conexionBD = new ConexionBD();
    $conn = $conexionBD->getConexionBD();

    // Preparar la consulta según el tipo
    $query = match ($tipo) {
        'seccion' => "SELECT ID_SECCION AS value, DESCRIPCION_SECCION AS text 
                      FROM SECCION 
                      WHERE ID_AREA = :id 
                      ORDER BY ID_SECCION",
        'linea' => "SELECT ID_LINEA AS value, DESCRIPCION_LINEA AS text 
                    FROM LINEA 
                    WHERE ID_SECCION = :id 
                    ORDER BY ID_LINEA",
        'familia' => "SELECT ID_FAMILIA AS value, DESCRIPCION_FAMILIA AS text 
                      FROM FAMILIA 
                      WHERE ID_LINEA = :id 
                      ORDER BY ID_FAMILIA",
        'subfamilia' => "SELECT ID_SUBFAMILIA AS value, DESCRIPCION_SUBFAMILIA AS text 
                         FROM SUBFAMILIA 
                         WHERE ID_FAMILIA = :id 
                         ORDER BY ID_SUBFAMILIA",
        default => throw new Exception("Tipo de consulta no válido: $tipo")
    };

    // Registrar la consulta
    file_put_contents($logFile, "Query: $query\n", FILE_APPEND);

    // Ejecutar la consulta
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        throw new Exception("Error ejecutando query: " . implode(", ", $stmt->errorInfo()));
    }

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir claves a minúsculas
    $options = array_map(fn($row) => array_change_key_case($row, CASE_LOWER), $options);

    // Registrar resultados
    file_put_contents($logFile, "Resultados encontrados: " . count($options) . "\n", FILE_APPEND);

    // Respuesta JSON
    echo json_encode([
        'success' => true,
        'options' => $options,
        'debug' => [
            'tipo' => $tipo,
            'id' => $id,
            'count' => count($options)
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Manejo de errores
    $errorMessage = sprintf("[%s] ERROR: %s\n", date('Y-m-d H:i:s'), $e->getMessage());
    file_put_contents($logFile, $errorMessage, FILE_APPEND);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'tipo' => $_GET['tipo'] ?? null,
            'id' => $_GET['id'] ?? null
        ]
    ], JSON_UNESCAPED_UNICODE);
}
?>