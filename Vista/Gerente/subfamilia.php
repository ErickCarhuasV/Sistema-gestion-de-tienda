<?php
require_once '../../Conexion/bd.php'; // Incluir la clase de conexión
require_once 'productoDao.php'; // Incluir el DAO de subfamilia
$conexionBD = new ConexionBD();
$subfamiliaDao = new SubfamiliaDao($conexionBD->getConexionBD());

$subfamilias = $subfamiliaDao->listarSubfamilias(); // Obtener las subfamilias
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prodc2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Lista de Subfamilias</title>
    <style>
        .form-container {
            display: none; /* Ocultar el formulario inicialmente */
        }
        .form-container form {
            width: 300px;
            text-align: center;
        }
    </style>
    
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
        <center><h2>Lista de Subfamilias</h2></center>
        <div class="center">
            <div class="search-container">
                <input type="text" id="buscarSubfamilia" class="search-bar" placeholder="Buscar subfamilia..." onkeyup="filtrarTabla()">
            </div>
        </div>
        <div class="table-responsive">
            <table id="subfamiliaTable">
                <thead>
                    <tr>
                        <th>ID Subfamilia</th>
                        <th>Descripción</th>

                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subfamilias as $subfamilia): ?>
                <tr>
                    <td><?php echo $subfamilia->getIdSubfamilia(); ?></td>
                    <td><?php echo $subfamilia->getDescripcionSubfamilia(); ?></td>
                    <td>
                    <button class="edit-button" onclick="window.location.href='editar_subfamilia.php?id=<?php echo $subfamilia->getIdSubfamilia(); ?>'">✏️</button>
                    </td>
                </tr>
                <?php endforeach; ?>
               </tbody>
            </table>
        </div>
        <button onclick="toggleAgregarSubfamilia()" id="btn-agregar">Agregar Subfamilia</button>
        <div id="agregarSubfamiliaForm" style="display: none;">
            <form id="formAgregarSubfamilia" method="POST" action="agregar_subfamilia.php">
                <label for="descripcion">Descripción de la Subfamilia:</label>
                    <input type="text" id="descripcion" name="descripcion" required>

                <label for="idFamilia">Familia:</label>
                    <select id="idFamilia" name="idFamilia" required>
                        <?php
                        // Consulta para obtener las familias disponibles
                        $familiaDao = new FamiliaDao($conexionBD->getConexionBD());
                        $familias = $familiaDao->listarFamilias(); // Asumiendo que existe este método
                        foreach ($familias as $familia) {
                            echo "<option value='" . htmlentities($familia->getIdFamilia()) . "'>" . htmlentities($familia->getDescripcion()) . "</option>";
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
            let input = document.getElementById("buscarSubfamilia");
            let filter = input.value.toLowerCase();
            let table = document.getElementById("subfamiliaTable");
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

        function toggleAgregarSubfamilia() {
            var form = document.getElementById("agregarSubfamiliaForm");
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>

</body>
</html>
