<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$marcaDao = new MarcaDao($conexionBD->getConexionBD());

$idMarca = $_GET['id'];
$marca = $marcaDao->obtenerMarcaPorId($idMarca);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editar.css">
    <center><title>Editar Marca</title><center/>
</head>
<body>
    
    <form action="procesar_editar_marca.php" method="POST">
    <center><h2>Editar Marca</h2><center/>
        <input type="hidden" name="idMarca" value="<?php echo htmlspecialchars($marca->getIdMarca()); ?>">
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($marca->getDescripcion()); ?>" required>
        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>
