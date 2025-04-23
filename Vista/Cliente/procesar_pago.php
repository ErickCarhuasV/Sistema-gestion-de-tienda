<?php
session_start();

// Verificar si hay productos en el carrito
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header('Location: Productos.php');
    exit;
}

// Calcular el total asegurando que los valores sean numéricos
$total = array_reduce($_SESSION['carrito'], function ($carry, $item) {
    $precio = floatval($item['precio']); // Convertir a float
    $cantidad = intval($item['cantidad']); // Convertir a integer
    return $carry + ($precio * $cantidad);
}, 0);

// Obtener el tipo de tarjeta seleccionado
$tipoTarjeta = isset($_POST['tipoTarjeta']) ? $_POST['tipoTarjeta'] : '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f8ff;
        }
        .resumen-compra {
            background-color: #ffffe0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ffcc00;
        }
        .card {
            border: 1px solid #004085;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card .card-body {
            background-color: #e3f2fd;
        }
        h2 {
            color: #004085;
        }
        h4 {
            color: #ffcc00;
        }
        .btn-primary {
            background-color: #004085;
            border-color: #004085;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #ffcc00;
            border-color: #ffcc00;
            color: #004085;
        }
        .btn-secondary:hover {
            background-color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2><i class="fas fa-credit-card"></i> Finalizar Compra</h2>
        
        <!-- Resumen de productos -->
        <div class="resumen-compra">
            <h4>Resumen de Productos</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['carrito'] as $producto): ?>
                            <?php 
                                $precio = floatval($producto['precio']);
                                $cantidad = intval($producto['cantidad']);
                                $subtotal = $precio * $cantidad;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                <td><?= $cantidad ?></td>
                                <td>$<?= number_format($subtotal, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total:</strong></td>
                            <td><strong>$<?= number_format($total, 2) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Formulario de pago -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Datos de Pago</h4>
                        <form id="formPago" action="confirmar_pago.php" method="POST">
                            <input type="hidden" name="tipoTarjeta" value="<?= htmlspecialchars($tipoTarjeta) ?>">
                            
                            <div class="mb-3">
                                <label for="numeroTarjeta" class="form-label">Número de Tarjeta</label>
                                <input type="text" class="form-control" id="numeroTarjeta" name="numeroTarjeta" 
                                       required pattern="\d{16}" maxlength="16" placeholder="1234 5678 9012 3456">
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label for="fechaExp" class="form-label">Fecha de Expiración</label>
                                    <input type="text" class="form-control" id="fechaExp" name="fechaExp" 
                                           required pattern="\d{2}/\d{2}" maxlength="5" placeholder="MM/YY">
                                </div>
                                <div class="col">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" 
                                           required pattern="\d{3,4}" maxlength="4" placeholder="123">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-lock"></i> Confirmar Pago
                            </button>
                            
                            <a href="detallefinalcliente.php" class="btn btn-secondary w-100">
                                <i class="fas fa-user"></i> Detalles de su Perfil
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Formatear número de tarjeta
        document.getElementById('numeroTarjeta').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });

        // Formatear fecha de expiración
        document.getElementById('fechaExp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substr(0,2) + '/' + value.substr(2);
            }
            e.target.value = value;
        });

        // Formatear CVV
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>