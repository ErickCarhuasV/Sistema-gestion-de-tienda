<?php
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

$conexionBD = new ConexionBD();
$familiaDao = new FamiliaDao($conexionBD->getConexionBD());
$lineaDao = new LineaDao($conexionBD->getConexionBD()); // DAO de Línea para obtener las opciones de línea

// Obtiene el ID de la familia a editar desde los parámetros GET
$idFamilia = $_GET['id'];
$familia = $familiaDao->obtenerFamiliaPorId($idFamilia);

// Obtiene la lista de líneas para el dropdown
$lineas = $lineaDao->listarLineas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar.css">
    <center><title>Editar Familia</title><center/>
</head>
<body>

<!-- Formulario de edición -->
<form action="procesar_editar_familia.php" method="post">
<center><h2>Editar Familia</h2><center/>
    <input type="hidden" name="idFamilia" value="<?php echo htmlentities($familia->getIdFamilia()); ?>">
    
    <label for="descripcionFamilia">Descripción:</label>
    <input type="text" name="descripcionFamilia" id="descripcionFamilia" 
           value="<?php echo htmlentities($familia->getDescripcion()); ?>" required>
    
    <label for="idLinea">Línea:</label>
    <select name="idLinea" id="idLinea" required>
        <?php foreach ($lineas as $linea): ?>
            <option value="<?php echo $linea->getIdLinea(); ?>"
                <?php if ($linea->getIdLinea() == $familia->getIdLinea()) echo 'selected'; ?>>
                <?php echo htmlentities($linea->getDescripcion()); // Asegúrate de usar getDescripcion() aquí ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>