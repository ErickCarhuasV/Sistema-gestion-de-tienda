<?php 
require_once '../../Conexion/bd.php'; // Asegúrate de que la clase de conexión está correctamente incluida
require_once 'productoDao.php'; // Incluir el DAO de familia


$conexionBD = new ConexionBD();
$familiaDao = new FamiliaDao($conexionBD->getConexionBD());
$lineaDao = new LineaDao($conexionBD->getConexionBD()); // Crear instancia del DAO de línea
$familias = $familiaDao->listarFamilias(); // Obtener las familias
$lineas = $lineaDao->listarLineas(); // Obtener las líneas
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prodc2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Lista de Familias</title>
    <style>

        .form-container {
            display: none; /* Ocultar el formulario inicialmente */
        }
        .form-container form,  {
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
    <center><h2>Lista de Familias</h2></center>

    <div class="center">
        <div class="search-container">
            <input type="text" id="buscarFamilia" class="search-bar" placeholder="Buscar familia..." onkeyup="filtrarTabla()">
        </div>
    </div>

    <div class="table-responsive">
        <table id="familiaTable">
            <thead>
                <tr>
                    <th>ID Familia</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($familias as $familia): ?>
                <tr>
                    <td><?php echo htmlspecialchars($familia->getIdFamilia()); ?></td>
                    <td><?php echo htmlspecialchars($familia->getDescripcion()); ?></td>
                    <td><button class="edit-button" onclick="window.location.href='editar_familia.php?id=<?php echo urlencode($familia->getIdFamilia()); ?>'">✏️</button></td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <button onclick="toggleForm()" id="btn-agregar">Agregar Familia</button>
        <div class="form-container" id="formContainer">
            <form action="agregar_familia.php" method="POST">
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required>

                <label for="idLinea">Línea:</label>
                <select id="idLinea" name="idLinea" required>
                    <?php foreach ($lineas as $linea): ?>
                        <option value="<?php echo htmlentities($linea->getIdLinea()); ?>">
                            <?php echo htmlentities($linea->getDescripcion()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" value="Agregar" id="submitButton" onclick="this.disabled=true; this.form.submit();">
            </form>
        </div>
</div>

<script>
    function filtrarTabla() {
        let input = document.getElementById("buscarFamilia");
        let filter = input.value.toLowerCase();
        let table = document.getElementById("familiaTable");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                let txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }

    function toggleForm() {
        const formContainer = document.getElementById("formContainer");
        formContainer.style.display = formContainer.style.display === "none" || formContainer.style.display === "" ? "block" : "none";
    }
</script>

</body>
</html>
