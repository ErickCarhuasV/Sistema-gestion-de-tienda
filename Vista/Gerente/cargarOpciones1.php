<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

// Crear archivo de log
$logFile = __DIR__ . '/debug_' . date('Y-m-d') . '.log';

try {
    // Validar parámetros
    if (!isset($_GET['tipo']) || !isset($_GET['id'])) {
        throw new Exception("Parámetros requeridos faltantes");
    }

    $tipo = $_GET['tipo'];
    $id = intval($_GET['id']);

    // Registrar la solicitud
    file_put_contents($logFile, "\n" . date('Y-m-d H:i:s') . " - Nueva solicitud\n", FILE_APPEND);
    file_put_contents($logFile, "Tipo: $tipo, ID: $id\n", FILE_APPEND);

    require_once '../../Conexion/bd.php';
    $conexionBD = new ConexionBD();
    $conn = $conexionBD->getConexionBD();

    // Preparar la consulta según el tipo
    switch ($tipo) {
        case 'provincia':
            $query = "SELECT ID_PROVINCIA as value, NOMBRE_PROVINCIA as text 
                     FROM PROVINCIA 
                     WHERE ID_REGION = :id 
                     ORDER BY NOMBRE_PROVINCIA";
            break;
        case 'distrito':
            $query = "SELECT ID_DISTRITO as value, NOMBRE_DISTRITO as text 
                     FROM DISTRITO 
                     WHERE ID_PROVINCIA = :id 
                     ORDER BY NOMBRE_DISTRITO";
            break;
        default:
            throw new Exception("Tipo de consulta no válido: $tipo");
    }

    // Registrar la consulta
    file_put_contents($logFile, "Query: $query\n", FILE_APPEND);

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        throw new Exception("Error ejecutando query: " . implode(", ", $stmt->errorInfo()));
    }

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir claves a minúsculas
    $options = array_map(function($row) {
        return array_change_key_case($row, CASE_LOWER);
    }, $options);

    // Registrar resultados
    file_put_contents($logFile, "Resultados encontrados: " . count($options) . "\n", FILE_APPEND);

    $response = [
        'success' => true,
        'options' => $options,
        'debug' => [
            'tipo' => $tipo,
            'id' => $id,
            'count' => count($options)
        ]
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    $error = [
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'tipo' => $_GET['tipo'] ?? null,
            'id' => $_GET['id'] ?? null
        ]
    ];

    // Registrar error
    file_put_contents($logFile, "ERROR: " . $e->getMessage() . "\n", FILE_APPEND);

    http_response_code(500);
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}
?>
