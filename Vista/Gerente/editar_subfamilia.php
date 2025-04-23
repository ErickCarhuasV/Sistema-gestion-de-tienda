<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$subfamiliaDao = new SubfamiliaDao($conexionBD->getConexionBD());
$familiaDao = new FamiliaDao($conexionBD->getConexionBD());

// Obtiene el ID de la subfamilia a editar desde los parámetros GET
$idSubfamilia = $_GET['id'];
$subfamilia = $subfamiliaDao->obtenerSubfamilia($idSubfamilia);

// Aquí verificas si la subfamilia fue encontrada
if (!$subfamilia) {
    echo "Subfamilia no encontrada.";
    exit;
}



// Obtiene la lista de familias para el dropdown
$familias = $familiaDao->listarFamilias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar.css">
    <title>Editar Subfamilia</title>
</head>
<body>

<!-- Formulario de edición -->
<form action="procesar_editar_subfamilia.php" method="post">
<center><h2>Editar Subfamilia</h2><center/>
    <input type="hidden" name="idSubfamilia" value="<?php echo htmlentities($subfamilia->getIdSubfamilia()); ?>">
    
    <label for="descripcionSubfamilia">Descripción:</label>
    <input type="text" name="descripcionSubfamilia" id="descripcionSubfamilia" 
           value="<?php echo htmlentities($subfamilia->getDescripcionSubfamilia()); ?>" required>
    
    <label for="idFamilia">Familia:</label>
    <select name="idFamilia" id="idFamilia" required>
        <?php foreach ($familias as $familia): ?>
            <option value="<?php echo htmlentities($familia->getIdFamilia()); ?>"
                <?php if ($familia->getIdFamilia() == $subfamilia->getIdFamilia()) echo 'selected'; ?>>
                <?php echo htmlentities($familia->getDescripcion()); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>


