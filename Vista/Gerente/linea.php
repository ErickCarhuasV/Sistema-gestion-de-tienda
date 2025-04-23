<?php
require_once '../../Conexion/bd.php'; // Incluir la clase de conexión
require_once 'productoDao.php'; // Incluir el DAO de línea
$conexionBD = new ConexionBD();
$lineaDao = new LineaDao($conexionBD->getConexionBD());



$lineas = $lineaDao->listarLineas(); // Obtener las líneas
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prodc2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Lista de Líneas</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Mostrar u ocultar el formulario de agregar línea
        function toggleAgregarLinea() {
            const form = document.getElementById('agregarLineaForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
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
        <center><h2>Lista de Líneas</h2><center/>
        <!-- Campo de búsqueda -->
        <div class="search-container">
        <input type="text" id="buscarLinea" class="search-bar" placeholder="Buscar línea..." onkeyup="filtrarTabla()">
        </div>
        <div class="table-responsive">
            <table id="lineaTable">
                <thead>
                    <tr>
                        <th>ID Línea</th>
                        <th>Descripción</th>
                        <th>Acciones</th> <!-- Columna para editar -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lineas as $linea): ?>
                        <tr>
                            <td><?php echo htmlentities($linea->getIdLinea()); ?></td>
                            <td><?php echo htmlentities($linea->getDescripcion()); ?></td>
                            <td>
                            <button class="edit-button" onclick="window.location.href='editar_linea.php?id=<?php echo htmlentities($linea->getIdLinea()); ?>'">✏️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Botón para desplegar el formulario de agregar línea -->
        <button onclick="toggleAgregarLinea()" id="btn-agregar">Agregar linea</button>

        <div id="agregarLineaForm" style="display: none;">
            <form id="formAgregarLinea" method="POST" action="agregar_linea.php">
                <label for="descripcion">Descripción de la Línea:</label>
                <input type="text" id="descripcion" name="descripcion" required>

                <label for="idSeccion">Sección:</label>
                    <select id="idSeccion" name="idSeccion" required>
                <?php
                // Consulta para obtener las secciones disponibles
                $seccionDao = new SeccionDao($conexionBD->getConexionBD());
                $secciones = $seccionDao->listarSecciones();
                foreach ($secciones as $seccion) {
                    echo "<option value='" . htmlentities($seccion->getIdSeccion()) . "'>" . htmlentities($seccion->getDescripcion()) . "</option>";
                }
                ?>
            </select>

        <button type="submit">Guardar</button>
    </form>
</div>
    </div>

    <script>
        // Función para filtrar la tabla según la búsqueda
        function filtrarTabla() {
            let input = document.getElementById("buscarLinea");
            let filter = input.value.toLowerCase();
            let table = document.getElementById("lineaTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName("td")[1];
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

