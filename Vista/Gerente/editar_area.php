<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$areaDao = new AreaDao($conexionBD->getConexionBD());

// Obtiene el ID del área a editar desde los parámetros GET
$idArea = $_GET['id'];
$area = $areaDao->obtenerAreaPorId($idArea);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar.css">
    <center><title>Editar Área</title><center/>
</head>
<body>

<!-- Formulario para editar el área -->
<form action="procesar_editar_area.php?id=<?php echo $area->getIdArea(); ?>" method="POST">
<center><h2>Editar Área</h2><center/>
    <label for="descripcion">Descripción:</label>
    <input type="text" id="descripcion" name="descripcion" value="<?php echo $area->getDescripcion(); ?>" required>
    <input type="submit" value="Guardar Cambios">
</form>

</body>
</html>
