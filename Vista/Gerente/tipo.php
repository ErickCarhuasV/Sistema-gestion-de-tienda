<?php
require_once '../../Conexion/bd.php'; // Incluir la clase de conexión
require_once 'productoDao.php'; // Incluir el DAO de tipo
$conexionBD = new ConexionBD();
$tipoDao = new TipoDao($conexionBD->getConexionBD());

$tipos = $tipoDao->listarTipos(); // Obtener los tipos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prodc2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Lista de Tipos</title>
</head>
<body>

<div class="sidebar">
    <h1>Panel de Control</h1>
    <ul class="sidebar-nav">
        <li><a href="Usuario.php"><i class="fas fa-home"></i> Inicio</a></li>
        <li><a href="productos.php"><i class="fas fa-box"></i> Productos</a></li>
        <li><a href="area.php"><i class="fas fa-layer-group"></i> Área</a></li>
        <li><a href="seccion.php"><i class="fas fa-columns"></i> Sección</a></li>
        <li><a href="linea.php"><i class="fas fa-chart-line"></i> Línea</a></li>
        <li><a href="familia.php"><i class="fas fa-users"></i> Familia</a></li>
        <li><a href="subfamilia.php"><i class="fas fa-sitemap"></i> Subfamilia</a></li>
        <li><a href="marca.php"><i class="fas fa-tag"></i> Marca</a></li>
        <li><a href="tipo.php"><i class="fas fa-clipboard-list"></i> Tipo</a></li>
    </ul>
</div>

    <div class="main-content">
        <center><h2>Lista de Tipos</h2></center>

        <!-- Campo de búsqueda -->
        <div class="search-container">
        <input type="text" id="buscarTipo" class="search-bar" placeholder="Buscar tipo..." onkeyup="filtrarTabla()">
        </div>

        <div class="table-responsive">
            <table id="tipoTable">
                <thead>
                    <tr>
                        <th>ID Tipo</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tipos as $tipo): ?>
                        <tr>
                            <td><?php echo $tipo->getIdTipo(); ?></td>
                            <td><?php echo $tipo->getDescripcion(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Función para filtrar la tabla según la búsqueda
        function filtrarTabla() {
            let input = document.getElementById("buscarTipo");
            let filter = input.value.toLowerCase();
            let table = document.getElementById("tipoTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName("td")[1]; // Buscar en la columna de descripción
                if (td) {
                    let txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

</body>
</html>