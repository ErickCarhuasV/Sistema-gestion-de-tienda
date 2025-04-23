<?php
require_once '../../Conexion/bd.php'; // Incluir la clase de conexión
require_once 'productoDao.php'; // Incluir el DAO de sección
$conexionBD = new ConexionBD();
$seccionDao = new SeccionDao($conexionBD->getConexionBD());
$areaDao = new AreaDao($conexionBD->getConexionBD());
$secciones = $seccionDao->listarSecciones(); // Obtener las secciones
$areas = $areaDao->listarAreas(); // Obtener las áreas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prodc2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Iconos de Font Awesome -->
    <title>Lista de Secciones</title>
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
    <center><h2>Lista de Secciones</h2></center>
    
    <div class="center">
        <div class="search-container">
            <input type="text" id="buscarSeccion" class="search-bar" placeholder="Buscar sección..." onkeyup="filtrarTabla()">
        </div>
    </div>

    <div class="table-responsive">
        <table id="seccionTable">
            <thead>
                <tr>
                    <th>ID Sección</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($secciones as $seccion): ?>
                    <tr>
                        <td><?php echo $seccion->getIdSeccion(); ?></td>
                        <td><?php echo $seccion->getDescripcion(); ?></td>
                        <td><button class="edit-button" onclick="window.location.href='editar_seccion.php?id=<?php echo $seccion->getIdSeccion(); ?>'"><i class="fas fa-edit"></i></button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <button onclick="toggleForm()" id="btn-agregar"><i class="fas fa-plus"></i> Agregar Sección</button>
    <div class="form-container" id="formContainer">
        <form action="agregar_seccion.php" method="POST">
            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required>
            
            <label for="id_area">Área:</label>
            <select id="id_area" name="id_area" required>
                <?php foreach ($areas as $area): ?>
                    <option value="<?php echo htmlentities($area->getIdArea()); ?>"><?php echo htmlentities($area->getDescripcion()); ?></option>
                <?php endforeach; ?>
            </select>
            
            <input type="submit" value="Guardar" id="submitButton" onclick="this.disabled=true; this.form.submit();">
        </form>
    </div>
</div>

<script>
    function filtrarTabla() {
        let input = document.getElementById("buscarSeccion");
        let filter = input.value.toLowerCase();
        let table = document.getElementById("seccionTable");
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
        formContainer.style.display = (formContainer.style.display === "none" || formContainer.style.display === "") ? "block" : "none";
    }
</script>

</body>
</html>
