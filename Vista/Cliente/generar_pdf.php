<?php 
require_once('vendor/autoload.php');

class FacturaPDF extends TCPDF {
    // Encabezado con color azul y ajuste de posición
    public function Header() {
        $this->SetFillColor(0, 50, 160); // Azul característico
        $this->Rect(0, 0, $this->getPageWidth(), 25, 'F'); // Rectángulo para encabezado
        $this->SetFont('helvetica', 'B', 15);
        $this->SetTextColor(255, 255, 255); // Texto blanco
        $this->SetY(10); // Ajuste para que el texto sea visible
        $this->Cell(0, 10, 'Confirmación de Compra', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Pie de página con contacto
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 9);
        $this->SetTextColor(128, 128, 128); // Gris claro
        $this->Cell(0, 10, 'Gracias por tu compra - Tiendas Mass | Contacto: 0800-MASS', 0, 0, 'C');
    }
}

function generarPDF($datos_compra, $productos) {
    $pdf = new FacturaPDF();
    $pdf->SetMargins(15, 30, 15); // Márgenes ajustados
    $pdf->AddPage();
    
    // Estilo del cuerpo del PDF
    $pdf->SetFont('helvetica', '', 12);

    $pdf->SetFillColor(255, 230, 0); // Amarillo
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(255, 230, 0); // Amarillo
    $pdf->Cell(0, 10, 'Información de la Compra', 1, 1, 'C', 1);
    $pdf->Ln(5);

    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(50, 8, 'Fecha:', 0, 0);
    $pdf->Cell(0, 8, date('d/m/Y H:i:s'), 0, 1);
    $pdf->Cell(50, 8, 'Número de Orden:', 0, 0);
    $pdf->Cell(0, 8, $datos_compra['orden_id'], 0, 1);
    $pdf->Ln(5);

    // Sección: Tabla de productos
    $pdf->SetFillColor(0, 50, 160); // Azul oscuro
    $pdf->SetTextColor(255, 255, 255); // Texto blanco
    $pdf->SetFont('helvetica', 'B', 12);

    $pdf->Cell(90, 10, 'Producto', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C', 1);
    $pdf->Cell(35, 10, 'Precio', 1, 0, 'C', 1);
    $pdf->Cell(35, 10, 'Subtotal', 1, 1, 'C', 1);

    $total = 0;
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0); // Texto negro
    foreach ($productos as $producto) {
        $precio = floatval(str_replace(',', '.', $producto['precio']));
        $cantidad = intval($producto['cantidad']);
        $subtotal = $precio * $cantidad;
        $total += $subtotal;

        $pdf->Cell(90, 8, $producto['nombre'], 1);
        $pdf->Cell(30, 8, $cantidad, 1, 0, 'C');
        $pdf->Cell(35, 8, '$'.number_format($precio, 2), 1, 0, 'R');
        $pdf->Cell(35, 8, '$'.number_format($subtotal, 2), 1, 1, 'R');
    }

    // Total
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetFillColor(255, 230, 0); // Amarillo
    $pdf->Cell(155, 10, 'Total', 1, 0, 'R', 1);
    $pdf->Cell(35, 10, '$'.number_format($total, 2), 1, 1, 'R', 1);

    // Pie interactivo
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(0, 10, 'Recuerda: Guarda tu boleta para futuras referencias.', 0, 1, 'C');

    return $pdf;
}
?>
