<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

// Inicializa la conexión y DAO
$conexionBD = new ConexionBD();
$lineaDao = new LineaDao($conexionBD->getConexionBD());
$seccionDao = new SeccionDao($conexionBD->getConexionBD());

// Obtiene el ID de la línea a editar desde los parámetros GET
$idLinea = $_GET['id'];
$linea = $lineaDao->obtenerLineaPorId($idLinea);

// Obtiene la lista de secciones para el dropdown
$secciones = $seccionDao->listarSecciones();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar.css">
    <center><title>Editar Línea</title><center/>
</head>
<body>


<!-- Formulario de edición -->
<form action="procesar_editar_linea.php" method="post">
<center><h2>Editar Línea</h2><center/>
    <!-- ID oculto para identificar la línea a editar -->
    <input type="hidden" name="idLinea" value="<?php echo htmlentities($linea->getIdLinea()); ?>">
    
    <!-- Campo para editar la descripción de la línea -->
    <label for="descripcionLinea">Descripción:</label>
    <input type="text" name="descripcionLinea" id="descripcionLinea" 
           value="<?php echo htmlentities($linea->getDescripcion()); ?>" required>
    
    <!-- Dropdown para seleccionar la sección de la línea -->
    <label for="idSeccion">Sección:</label>
    <select name="idSeccion" id="idSeccion" required>
        <?php foreach ($secciones as $seccion): ?>
            <option value="<?php echo $seccion->getIdSeccion(); ?>"
                <?php if ($seccion->getIdSeccion() == $linea->getIdSeccion()) echo 'selected'; ?>>
                <?php echo htmlentities($seccion->getDescripcion()); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <!-- Botón para enviar el formulario -->
    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>