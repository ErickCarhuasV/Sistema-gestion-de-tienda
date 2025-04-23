<?php
session_start();


require_once '../../Conexion/bd.php';
require_once 'productoDao.php';
function sendJsonResponse($data, $statusCode = 200) {
    ob_clean(); // Limpiar cualquier output previo
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}
// Initialize cart if it doesn't exist
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verificar si es una petición AJAX
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
         if (!$isAjax) {
            return;
        }
        // Obtener y validar la acción
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        if ($isAjax && !$action) {
            $action = 'agregar'; // Comportamiento por defecto para peticiones AJAX antiguas
        }
        if (!$action) {
            throw new Exception('Acción no especificada');
        }

        // Validar producto_id
        $productoId = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
        if (!$productoId) {
            throw new Exception('ID de producto inválido');
        }

        // Inicializar carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Manejar diferentes acciones
        switch ($action) {
            case 'eliminar':
                $_SESSION['carrito'] = array_values(array_filter($_SESSION['carrito'], function ($item) use ($productoId) {
                    return $item['id'] !== $productoId;
                }));
                $response = ['success' => true, 'message' => 'Producto eliminado del carrito'];
                break;

                case 'actualizar':
                    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
                    if ($cantidad === false || $cantidad <= 0) {
                        throw new Exception('Cantidad inválida, debe ser un número mayor a 0');
                    }
                
                    foreach ($_SESSION['carrito'] as &$item) {
                        if ($item['id'] === $productoId) {
                            $item['cantidad'] = $cantidad; // Actualizar la cantidad
                            break;
                        }
                    }
                    $response = ['success' => true, 'message' => 'Cantidad actualizada correctamente'];
                    break;

            case 'agregar':
                $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT) ?? 1;
                if ($cantidad <= 0) {
                    throw new Exception('Cantidad inválida');
                }

                // Verificar producto y stock
                $productoDAO = new ProductoDAO();
                $producto = $productoDAO->obtenerProductoPorID($productoId);
                if (!$producto) {
                    throw new Exception('Producto no encontrado');
                }

                // Buscar si el producto ya existe en el carrito
                $encontrado = false;
                foreach ($_SESSION['carrito'] as &$item) {
                    if ($item['id'] === $productoId) {
                        $item['cantidad'] += $cantidad;
                        $encontrado = true;
                        break;
                    }
                }

                // Si no existe, agregarlo
                if (!$encontrado) {
                    $_SESSION['carrito'][] = [
                        'id' => $productoId,
                        'nombre' => $producto->getNombre(),
                        'precio' => $producto->getPrecio(),
                        'cantidad' => $cantidad
                    ];
                }

                $response = [
                    'success' => true,
                    'message' => 'Producto agregado al carrito'
                ];
                
                // Agregar contador del carrito solo para peticiones AJAX
                if ($isAjax) {
                    $response['cartCount'] = count($_SESSION['carrito']);
                }
                break;

            default:
                throw new Exception('Acción no reconocida');
        }

        echo json_encode($response);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}
// Crear conexión a la base de datos
$con = new ConexionBD();
$conexion = $con->getConexionBD();

// Obtener tipos de tarjeta
$query_tarjetas = "SELECT ID_TIPO_CARJETA, NOMBRE FROM TIPO_CARJETA";
$stmt = $conexion->prepare($query_tarjetas);
$stmt->execute();
$resultado_tarjetas = $stmt->fetchAll(PDO::FETCH_ASSOC);

function obtenerProductosDelCarrito() {
    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
        return [];
    }

    return $_SESSION['carrito'];
}

// Obtener productos del carrito
$productosCarrito = obtenerProductosDelCarrito();
$total = array_reduce($productosCarrito, function ($carry, $item) {
    $precio = floatval($item['precio']);
    $cantidad = intval($item['cantidad']);
    return $carry + ($precio * $cantidad);
}, 0);
?>
<script>document.addEventListener('DOMContentLoaded', () => {
    // Función para manejar errores de fetch
    const handleFetchErrors = async (response) => {
        if (!response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const error = await response.json();
                throw new Error(error.message || 'Error en la solicitud');
            } else {
                throw new Error('Error en el servidor');
            }
        }
        return response.json();
    };

    // Función para eliminar producto
    window.eliminarProducto = function(productoId) {
        if (confirm('¿Está seguro que desea eliminar este producto del carrito?')) {
            const formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append('producto_id', productoId);

            fetch(window.location.href, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'  // Add this header
            },
            body: formData
        })
        .then(handleFetchErrors)
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Error al eliminar el producto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message);
        });
        document.querySelectorAll('input[type="number"]').forEach((input) => {
    input.addEventListener('input', (event) => {
        if (event.target.value <= 0) {
            event.target.value = 1; // Forzar mínimo válido
        }
    });
});
    }
    };

    // Función para actualizar cantidad
    let timeoutId;
    window.actualizarProducto = function (productoId, inputElement) {
    clearTimeout(timeoutId);
    
    timeoutId = setTimeout(() => {
        const cantidad = parseInt(inputElement.value);
        if (isNaN(cantidad) || cantidad <= 0) {
            alert('Por favor ingrese una cantidad válida mayor a 0');
            inputElement.value = 1; // Resetear al valor mínimo válido
            return;
        }

        const formData = new FormData();
        formData.append('action', 'actualizar');
        formData.append('producto_id', productoId);
        formData.append('cantidad', cantidad);

        fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Indicador de petición AJAX
            },
        })
            .then(handleFetchErrors)
            .then((data) => {
                if (data.success) {
                    // Actualizar la página para reflejar los cambios
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al actualizar la cantidad');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert(error.message);
            });
    }, 500);
};
});
</script>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
         body {
            background-color: #f0f8ff;
        }
        .producto-card {
            background-color: #fff;
            border: 1px solid #004085;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .producto-card h5 {
            color: #004085;
        }
        .btn-danger {
            background-color: #ffcc00;
            border-color: #ffcc00;
            color: #004085;
        }
        .btn-danger:hover {
            background-color: #ffc107;
            color: #002752;
        }
        .total-section {
            background-color: #004085;
            color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .total-section h4 {
            color: #ffcc00;
        }
        .btn-primary {
            background-color: #004085;
            border-color: #004085;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-select {
            background-color: #f0f8ff;
            border: 1px solid #004085;
        }
    </style>
</head>
<body>
<div class="container mt-4">
        <h2 class="text-primary"><i class="fa-solid fa-cart-shopping"></i> Detalle de tu Compra</h2>
        <div class="row mt-4">
            <div class="col-md-8">
                <h4 class="text-primary">Productos Seleccionados</h4>
                <?php if (!empty($productosCarrito)): ?>
                    <?php foreach ($productosCarrito as $producto): ?>
                        <div class="producto-card">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5><?= htmlspecialchars($producto['nombre']) ?></h5>
                                    <label for="cantidad_<?= $producto['id'] ?>">Cantidad:</label>
                                    <input type="number" id="cantidad_<?= $producto['id'] ?>" min="1" value="<?= intval($producto['cantidad']) ?>" class="form-control d-inline w-25" onchange="actualizarProducto(<?= $producto['id'] ?>, this)">
                                    <p>Precio unitario: S/<?= number_format(floatval($producto['precio']), 2) ?></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <h6>Subtotal: S/<?= number_format(floatval($producto['precio']) * intval($producto['cantidad']), 2) ?></h6>
                                    <button class="btn btn-danger btn-sm" onclick="eliminarProducto(<?= $producto['id'] ?>)"><i class="fas fa-trash"></i> Eliminar</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">No hay productos en el carrito</div>
                <?php endif; ?>
            </div>

            <!-- Sección de pago -->
            <div class="col-md-4">
                <div class="total-section">
                    <h4>Resumen de Compra</h4>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total:</span>
                        <strong>$<?= number_format($total, 2) ?></strong>
                    </div>
                    <form id="formPago" method="POST" action="procesar_pago.php">
                        <div class="mb-3">
                            <label for="tipoTarjeta" class="form-label">Elija su tipo de tarjeta:</label>
                            <select class="form-select" id="tipoTarjeta" name="tipoTarjeta" required>
                                <option value="">Seleccione una opción</option>
                                <?php foreach ($resultado_tarjetas as $tarjeta): ?>
                                    <option value="<?= $tarjeta['ID_TIPO_CARJETA'] ?>"><?= htmlspecialchars($tarjeta['NOMBRE']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-credit-card"></i> Proceder al Pago
                        </button>
                        <button type="button" class="btn btn-primary w-100" onclick="window.location.href='Productos.php'">Cancelar Compra</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
   

</body>
</html>
