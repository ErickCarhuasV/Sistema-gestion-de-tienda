<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$seccionDao = new SeccionDao($conexionBD->getConexionBD());
$areaDao = new AreaDao($conexionBD->getConexionBD());

// Obtiene el ID de la sección a editar desde los parámetros GET
$idSeccion = $_GET['id'];
$seccion = $seccionDao->obtenerSeccionPorId($idSeccion);

// Obtiene la lista de áreas para el dropdown
$areas = $areaDao->listarAreas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar.css">
    <title>Editar Sección</title>
</head>
<body>



<!-- Formulario de edición -->
<form action="procesar_editar_seccion.php" method="post">
<center><h2>Editar Sección</h2><center/>
    <input type="hidden" name="idSeccion" value="<?php echo htmlentities($seccion->getIdSeccion()); ?>">
    
    <label for="descripcionSeccion">Descripción:</label>
    <input type="text" name="descripcionSeccion" id="descripcionSeccion" 
           value="<?php echo htmlentities($seccion->getDescripcion()); ?>" required>
    
    <label for="idArea">Área:</label>
    <select name="idArea" id="idArea" required>
        <?php foreach ($areas as $area): ?>
            <option value="<?php echo $area->getIdArea(); ?>"
                <?php if ($area->getIdArea() == $seccion->getIdArea()) echo 'selected'; ?>>
                <?php echo htmlentities($area->getDescripcion()); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>