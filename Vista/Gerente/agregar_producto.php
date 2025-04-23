<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../Conexion/bd.php';
require_once 'productoDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mb_convert_encoding($_POST['nombre'], 'UTF-8', 'ISO-8859-1');
    $descripcion = mb_convert_encoding($_POST['descripcion'], 'UTF-8', 'ISO-8859-1');
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];
    $marca = mb_convert_encoding($_POST['marca'], 'UTF-8', 'ISO-8859-1');
    $idSubfamilia = $_POST['idSubfamilia'];
    $cantidad = $_POST['cantidad'];
    $idFamilia = $_POST['idFamilia'];
    $idLinea = $_POST['idLinea'];
    $idSeccion = $_POST['idSeccion'];
    $idArea = $_POST['idArea'];

    if (!empty($nombre) && !empty($descripcion) && !empty($precio) && !empty($tipo) && !empty($marca) && !empty($idSubfamilia) && !empty($cantidad) && !empty($idFamilia) && !empty($idLinea) && !empty($idSeccion) && !empty($idArea)) {
        $productoDAO = new ProductoDAO();
        $resultado = $productoDAO->agregarProducto($nombre, $descripcion, floatval($precio), intval($tipo), intval($marca), intval($idSubfamilia), intval($cantidad), intval($idFamilia), intval($idLinea), intval($idSeccion), intval($idArea));

        if ($resultado) {
            echo "<script>alert('Producto agregado exitosamente.'); window.location.href='productos.php';</script>";
        } else {
            echo "<script>alert('Error al agregar el producto.');</script>";
        }
    } else {
        echo "<script>alert('Todos los campos son obligatorios.');</script>";
    }
}

// Obtener listas para el formulario
$conexionBD = new ConexionBD();
$subfamiliaDao = new SubfamiliaDao($conexionBD->getConexionBD());
$subfamilias = $subfamiliaDao->listarSubfamilias();

$marcaDao = new MarcaDao($conexionBD->getConexionBD());
$tipoDao = new TipoDao($conexionBD->getConexionBD());
$marcas = $marcaDao->listarMarcas();
$tipos = $tipoDao->listarTipos();

$familiaDao = new FamiliaDao($conexionBD->getConexionBD());
$lineaDao = new LineaDao($conexionBD->getConexionBD());
$seccionDao = new SeccionDao($conexionBD->getConexionBD());
$areaDao = new AreaDao($conexionBD->getConexionBD());

$familias = $familiaDao->listarFamilias();
$lineas = $lineaDao->listarLineas();
$secciones = $seccionDao->listarSecciones();
$areas = $areaDao->listarAreas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="../../registro.css">
    <title>Agregar Producto</title>
</head>
<body>
<div class="register-container">
    <form id="addProductForm" method="POST" action="">
        <center><h2>Agregar Producto</h2></center>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="descripcion" placeholder="Descripción" required>
        <input type="text" name="precio" placeholder="Precio" required>
        <input type="text" name="cantidad" placeholder="Stock" required>

        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" required>
            <?php foreach ($tipos as $tipo): ?>
                <option value="<?= htmlentities($tipo->getIdTipo()) ?>"><?= htmlentities($tipo->getDescripcion()) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="marca">Marca:</label>
        <select id="marca" name="marca" required>
            <?php foreach ($marcas as $marca): ?>
                <option value="<?= htmlentities($marca->getIdMarca()) ?>">
                    <?= mb_convert_encoding(htmlentities($marca->getDescripcion()), 'UTF-8', 'ISO-8859-1') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="idArea">Área:</label>
        <select id="idArea" name="idArea" required>
            <option value="">Seleccione un área</option>
            <?php foreach ($areas as $area): ?>
                <option value="<?php echo htmlspecialchars($area->getIdArea()); ?>"><?php echo htmlspecialchars($area->getDescripcion()); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="idSeccion">Sección:</label>
        <select id="idSeccion" name="idSeccion" required>
            <option value="">Seleccione una seccion</option>
            <?php foreach ($secciones as $seccion): ?>
                <option value="<?= htmlentities($seccion->getIdSeccion()) ?>"><?= htmlentities($seccion->getDescripcion()) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="idLinea">Línea:</label>
        <select id="idLinea" name="idLinea" required>
            <?php foreach ($lineas as $linea): ?>
                <option value="<?= htmlentities($linea->getIdLinea()) ?>"><?= htmlentities($linea->getDescripcion()) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="idFamilia">Familia:</label>
        <select id="idFamilia" name="idFamilia" required>
             <?php foreach ($familias as $familia): ?>
                <option value="<?= htmlentities($familia->getIdFamilia()) ?>"><?= htmlentities($familia->getDescripcion()) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="idSubfamilia">Subfamilia:</label>
        <select id="idSubfamilia" name="idSubfamilia" required>
            <?php foreach ($subfamilias as $subfamilia): ?>
                <option value="<?= htmlentities($subfamilia->getIdSubFamilia()) ?>"><?= htmlentities($subfamilia->getDescripcionSubfamilia()) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Agregar Producto">
        <button type="button" onclick="window.location.href='productos.php'">Cancelar</button>
    </form>
</div>
<script>
   async function fetchData(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error;
    }
}

function updateSelect(selectElement, options, defaultText = 'Seleccione una opción') {
    selectElement.innerHTML = `<option value="">${defaultText}</option>`;
    
    if (Array.isArray(options)) {
        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option.value;
            optionElement.textContent = option.text;
            selectElement.appendChild(optionElement);
        });
    }
    
    selectElement.disabled = options.length === 0;
}

// Función para cargar secciones
async function cargarSecciones() {
    const areaSelect = document.getElementById('idArea');
    const seccionSelect = document.getElementById('idSeccion');
    const lineaSelect = document.getElementById('idLinea');
    const familiaSelect = document.getElementById('idFamilia');
    const subfamiliaSelect = document.getElementById('idSubfamilia');
    const areaId = areaSelect.value;

    // Deshabilitar y limpiar todos los selects dependientes
    seccionSelect.disabled = true;
    lineaSelect.disabled = true;
    familiaSelect.disabled = true;
    subfamiliaSelect.disabled = true;

    updateSelect(lineaSelect, [], 'Seleccione una sección primero');
    updateSelect(familiaSelect, [], 'Seleccione una línea primero');
    updateSelect(subfamiliaSelect, [], 'Seleccione una familia primero');

    if (!areaId) {
        updateSelect(seccionSelect, [], 'Seleccione un área primero');
        return;
    }

    try {
        seccionSelect.innerHTML = '<option value="">Cargando...</option>';
        const data = await fetchData(`cargarOpciones.php?tipo=seccion&id=${areaId}&timestamp=${Date.now()}`);
        
        if (data.success && Array.isArray(data.options)) {
            updateSelect(seccionSelect, data.options, 'Seleccione una sección');
            seccionSelect.disabled = false;
        } else {
            throw new Error(data.error || 'Error al cargar secciones');
        }
    } catch (error) {
        console.error('Error en cargarSecciones:', error);
        updateSelect(seccionSelect, [], 'Error al cargar secciones');
    }
}

// Nivel 2: Sección → Línea
async function cargarLineas() {
    const seccionSelect = document.getElementById('idSeccion');
    const lineaSelect = document.getElementById('idLinea');
    const familiaSelect = document.getElementById('idFamilia');
    const subfamiliaSelect = document.getElementById('idSubfamilia');
    const seccionId = seccionSelect.value;

    // Deshabilitar y limpiar selects dependientes
    lineaSelect.disabled = true;
    familiaSelect.disabled = true;
    subfamiliaSelect.disabled = true;

    updateSelect(familiaSelect, [], 'Seleccione una línea primero');
    updateSelect(subfamiliaSelect, [], 'Seleccione una familia primero');

    if (!seccionId) {
        updateSelect(lineaSelect, [], 'Seleccione una sección primero');
        return;
    }

    try {
        lineaSelect.innerHTML = '<option value="">Cargando...</option>';
        const data = await fetchData(`cargarOpciones.php?tipo=linea&id=${seccionId}&timestamp=${Date.now()}`);
        
        if (data.success && Array.isArray(data.options)) {
            updateSelect(lineaSelect, data.options, 'Seleccione una línea');
            lineaSelect.disabled = false;
        } else {
            throw new Error(data.error || 'Error al cargar líneas');
        }
    } catch (error) {
        console.error('Error en cargarLineas:', error);
        updateSelect(lineaSelect, [], 'Error al cargar líneas');
    }
}

// Nivel 3: Línea → Familia
async function cargarFamilias() {
    const lineaSelect = document.getElementById('idLinea');
    const familiaSelect = document.getElementById('idFamilia');
    const subfamiliaSelect = document.getElementById('idSubfamilia');
    const lineaId = lineaSelect.value;

    // Deshabilitar selects dependientes
    familiaSelect.disabled = true;
    subfamiliaSelect.disabled = true;

    if (!lineaId) {
        updateSelect(familiaSelect, [], 'Seleccione una línea primero');
        updateSelect(subfamiliaSelect, [], 'Seleccione una familia primero');
        return;
    }

    try {
        familiaSelect.innerHTML = '<option value="">Cargando...</option>';
        const data = await fetchData(`cargarOpciones.php?tipo=familia&id=${lineaId}&timestamp=${Date.now()}`);
        
        if (data.success && Array.isArray(data.options)) {
            updateSelect(familiaSelect, data.options, 'Seleccione una familia');
            familiaSelect.disabled = false;
        } else {
            throw new Error(data.error || 'Error al cargar familias');
        }
    } catch (error) {
        console.error('Error en cargarFamilias:', error);
        updateSelect(familiaSelect, [], 'Error al cargar familias');
    }
}

// Nivel 4: Familia → Subfamilia
async function cargarSubfamilias() {
    const familiaSelect = document.getElementById('idFamilia');
    const subfamiliaSelect = document.getElementById('idSubfamilia');
    const familiaId = familiaSelect.value;

    if (!familiaId) {
        updateSelect(subfamiliaSelect, [], 'Seleccione una familia primero');
        return;
    }

    try {
        subfamiliaSelect.innerHTML = '<option value="">Cargando...</option>';
        const data = await fetchData(`cargarOpciones.php?tipo=subfamilia&id=${familiaId}&timestamp=${Date.now()}`);
        
        if (data.success && Array.isArray(data.options)) {
            updateSelect(subfamiliaSelect, data.options, 'Seleccione una subfamilia');
            subfamiliaSelect.disabled = false;
        } else {
            throw new Error(data.error || 'Error al cargar subfamilias');
        }
    } catch (error) {
        console.error('Error en cargarSubfamilias:', error);
        updateSelect(subfamiliaSelect, [], 'Error al cargar subfamilias');
    }
}

// 3. CONFIGURACIÓN DE EVENT LISTENERS
document.addEventListener('DOMContentLoaded', function() {
    // Configurar los event listeners para los selects
    const areaSelect = document.getElementById('idArea');
    const seccionSelect = document.getElementById('idSeccion');
    const lineaSelect = document.getElementById('idLinea');
    const familiaSelect = document.getElementById('idFamilia');

    if (areaSelect) {
        areaSelect.addEventListener('change', cargarSecciones);
    }

    if (seccionSelect) {
        seccionSelect.addEventListener('change', cargarLineas);
    }

    if (lineaSelect) {
        lineaSelect.addEventListener('change', cargarFamilias);
    }

    if (familiaSelect) {
        familiaSelect.addEventListener('change', cargarSubfamilias);
    }
});
</script>
</body>
</html>