<?php
session_start();
require_once 'generar_pdf.php';
require_once '../../Conexion/bd.php';



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si hay productos en el carrito
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header('Location: Productos.php');
    exit;
}

// Verificar datos del formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: procesar_pago.php');
    exit;
}

try {
    $con = new ConexionBD();
    $conexion = $con->getConexionBD();
    
    // Iniciar transacción
    $conexion->beginTransaction();
    
    // Generar ID de orden único
    $orden_id = uniqid('ORD');
    
    // Datos para el PDF
    $datos_compra = [
        'orden_id' => $orden_id,
        'numero_tarjeta' => $_POST['numeroTarjeta'],
        'fecha' => date('Y-m-d H:i:s')
    ];
    
    // Guardar la orden en la base de datos
    $stmt = $conexion->prepare("INSERT INTO ORDENES (ORDEN_ID, FECHA, TOTAL) VALUES (:orden_id, TO_DATE(:fecha, 'YYYY-MM-DD HH24:MI:SS'), :total)");
    
    // Calcular total
    $total = array_reduce($_SESSION['carrito'], function($carry, $item) {
        return $carry + (floatval($item['precio']) * intval($item['cantidad']));
    }, 0);
    
    $stmt->execute([
        ':orden_id' => $orden_id,
        ':fecha' => date('Y-m-d H:i:s'),
        ':total' => $total
    ]);
    
    // Guardar detalles de la orden
    $stmt = $conexion->prepare("INSERT INTO ORDEN_DETALLES (ORDEN_ID, PRODUCTO_ID, CANTIDAD, PRECIO) VALUES (:orden_id, :producto_id, :cantidad, :precio)");
    
    foreach ($_SESSION['carrito'] as $producto) {
        $stmt->bindValue(':orden_id', $orden_id);
        $stmt->bindValue(':producto_id', $producto['id']);
        $stmt->bindValue(':cantidad', $producto['cantidad']);
        $stmt->bindValue(':precio', $producto['precio']);
        $stmt->execute();
    }
    
    // Confirmar transacción
    $conexion->commit();
    
    // Crear el directorio para los PDFs
    $pdf_dir = __DIR__ . DIRECTORY_SEPARATOR . 'comprobantes';
    if (!is_dir($pdf_dir)) {
        mkdir($pdf_dir, 0777, true);
        chmod($pdf_dir, 0777);
    }
    
    // Generar nombre de archivo y ruta completa
    $pdf_filename = 'compra_' . $orden_id . '.pdf';
    $pdf_full_path = $pdf_dir . DIRECTORY_SEPARATOR . $pdf_filename;
    
    try {
        // Intentar generar y guardar el PDF
        $pdf = generarPDF($datos_compra, $_SESSION['carrito']);
        if (!$pdf->Output($pdf_full_path, 'F')) {
            throw new Exception('No se pudo guardar el archivo PDF.');
        }
    } catch (Exception $pdfError) {
        error_log("Error al generar el PDF: " . $pdfError->getMessage());
        // Continuar sin PDF
    }
    
    // Limpieza del carrito
    unset($_SESSION['carrito']);
    
    // Mostrar pantalla de confirmación interactiva
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Compra Finalizada</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                background-color: #f8f9fa;
            }
            .confirmation-card {
                border: 1px solid #28a745;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin-top: 50px;
            }
            .confirmation-card .card-header {
                background-color: #28a745;
                color: white;
                font-size: 1.5rem;
            }
            .confirmation-card .btn-primary {
                background-color: #28a745;
                border-color: #28a745;
            }
            .confirmation-card .btn-primary:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="card confirmation-card">
                <div class="card-header text-center">
                    ¡Compra Finalizada!
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    <h2 class="my-3">¡Gracias por tu compra!</h2>
                    <p>Tu número de orden es: <strong><?= $orden_id ?></strong></p>
                    <div class="mt-4">
                        <a href="Productos.php" class="btn btn-primary me-2">
                            <i class="fas fa-shopping-cart"></i> Continuar Comprando
                        </a>
                        <a href="../../index.php" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Salir de tienda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
} catch (Exception $e) {
    // Rollback si hay error
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    
    error_log("Error en la transacción: " . $e->getMessage());
    ?>
    <div class="alert alert-danger">
        Lo sentimos, hubo un error al procesar tu pago. Por favor, intenta nuevamente.
        <br>Error: <?= htmlspecialchars($e->getMessage()) ?>
    </div>
    <?php
}
?>
