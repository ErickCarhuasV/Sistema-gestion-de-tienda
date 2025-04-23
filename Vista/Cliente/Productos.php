<?php
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

$productoDAO = new ProductoDAO();
$productos = $productoDAO->listarProductos(); 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="produc2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Catálogo de Productos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>

<body>
<div class="sidebar">
        <h1>Panel de Compras</h1>
        <ul class="sidebar-nav">
            <li><a href="Principal.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="configuracion.php"><i class="fa-solid fa-user"></i> Perfil</a></li>
            <li><a href="DetalleCompras.php"><i class="fa-solid fa-gear"></i> Detalle de compra</a></li>
            <li><a href="productos.php"><i class="fas fa-box"></i> Productos</a></li>
            <li><a><i class="fas fa-layer-group"></i> Área</a></li>
            <li>
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
            </li>
        </ul>
    </div>
    <center><div class="main-content">
                    <center><h2>Catálogo de Productos</h2></center>
                    <div class="search-container">
                        <input type="text" id="buscarProducto" placeholder="Buscar producto..." onkeyup="filtrarProductos()">
                    </div>

                    
            <div class="product-container" >
            <center><h2>Productos Disponibles para Comprar</h2></center>
                    <div class="product-grid" id="productGrid"></div>
                    <?php
                    
            foreach ($productos as $producto) {
                ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($producto->getImagen()); ?>" alt="Imagen de producto">
                    <h3><?php echo htmlspecialchars($producto->getNombre()); ?></h3>
                    <p><?php echo htmlspecialchars($producto->getDescripcion()); ?></p>
                    <p class="product-price">$<?php echo htmlspecialchars($producto->getPrecio()); ?></p>
                    <button class="agregar-carrito" data-id="<?php echo htmlspecialchars($producto->getIdProducto()); ?>">
                        Agregar al carrito
                    </button>
                </div>
                <?php
            }
            ?>
            </div>
        </div>
   

    <script>

function filtrarProductos() {
    let input = document.getElementById("buscarProducto");
    let filter = input.value.toLowerCase().trim();
    let productos = document.getElementsByClassName("product-card");

    for (let i = 0; i < productos.length; i++) {
        let producto = productos[i];
        let nombre = producto.querySelector("h3").textContent;
        let descripcion = producto.querySelector("p").textContent;
        
        if (nombre.toLowerCase().includes(filter) || 
            descripcion.toLowerCase().includes(filter)) {
            producto.style.display = "";
        } else {
            producto.style.display = "none";
        }
    }
}

async function agregarAlCarrito(productoId) {
    try {
        // Deshabilitar el botón mientras se procesa
        const boton = document.querySelector(`button[data-id="${productoId}"]`);
        if (boton) {
            boton.disabled = true;
        }

        const formData = new FormData();
        formData.append('producto_id', productoId);
        formData.append('cantidad', 1);

        const response = await fetch('DetalleCompras.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin', // Importante para manejar sesiones
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Verificar si la respuesta es JSON válido
        let result;
        const text = await response.text(); // Primero obtenemos el texto de la respuesta
        try {
            result = JSON.parse(text); // Intentamos parsearlo como JSON
        } catch (e) {
            console.error('Respuesta no válida:', text);
            throw new Error('La respuesta del servidor no es JSON válido');
        }

        if (result.success) {
            // Mostrar mensaje de éxito
            mostrarMensaje('Producto agregado al carrito exitosamente', 'success');
            
            // Actualizar el contador del carrito si existe
            if (result.cartCount) {
                actualizarContadorCarrito(result.cartCount);
            }

            // Si estamos en la página del carrito, recargar después de un breve delay
            if (window.location.href.includes('DetalleCompras.php')) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        } else {
            throw new Error(result.message || 'Error al agregar al carrito');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarMensaje(error.message || 'Error al agregar el producto', 'error');
    } finally {
        // Reactivar el botón
        const boton = document.querySelector(`button[data-id="${productoId}"]`);
        if (boton) {
            boton.disabled = false;
        }
    }
}

// Función mejorada para mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
    // Crear o obtener el contenedor de mensajes
    let contenedor = document.querySelector('.mensajes-container');
    if (!contenedor) {
        contenedor = document.createElement('div');
        contenedor.className = 'mensajes-container';
        contenedor.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 300px;
        `;
        document.body.appendChild(contenedor);
    }

    // Crear el mensaje
    const mensajeElement = document.createElement('div');
    mensajeElement.className = `alert alert-${tipo}`;
    mensajeElement.style.cssText = `
        margin-bottom: 10px;
        padding: 15px;
        border-radius: 4px;
        background-color: ${tipo === 'success' ? '#d4edda' : '#f8d7da'};
        color: ${tipo === 'success' ? '#155724' : '#721c24'};
        border: 1px solid ${tipo === 'success' ? '#c3e6cb' : '#f5c6cb'};
    `;
    mensajeElement.textContent = mensaje;

    // Agregar el mensaje al contenedor
    contenedor.appendChild(mensajeElement);

    // Remover el mensaje después de 3 segundos si no es un error
    if (tipo !== 'error') {
        setTimeout(() => {
            mensajeElement.remove();
        }, 3000);
    }
}

// Función para actualizar el contador del carrito
function actualizarContadorCarrito(count) {
    const contador = document.querySelector('.cart-count');
    if (contador) {
        contador.textContent = count;
    }
}

        function cargarCarrito() {
            $.ajax({
                url: 'DetalleCompras.php', // URL para obtener la lista de productos en el carrito
                method: 'GET',
                success: function(html) {
                    $('#productosSeleccionados').html(html); // Asume que hay un contenedor para los productos
                },
                error: function() {
                    alert('Error al cargar el carrito.');
                }
            });
        }
        
        $(document).ready(function () {
            // Escuchar clic en el botón "Agregar al carrito"
            $(".agregar-carrito").click(function () {
                const productoId = $(this).data("id"); // Asegúrate de tener data-id en el botón
                agregarAlCarrito(productoId);
            });
        });

        function crearTarjetaProducto(producto) {
    return `
        <div class="product-card">
            <img src="${producto.imagen || 'placeholder.jpg'}" alt="${producto.nombre}" 
                 onerror="this.src='placeholder.jpg'">
            <h3>${producto.nombre}</h3>
            <p>${producto.descripcion || ''}</p>
            <p class="precio">$${parseFloat(producto.precio).toFixed(2)}</p>
            <button class="agregar-carrito" data-id="${producto.id}">
                Agregar al carrito
            </button>
        </div>
    `;
}
        async function cargarProductos() {
    try {
        // Obtener valores de los filtros
        const filtros = {
            areaId: document.getElementById('idArea').value,
            seccionId: document.getElementById('idSeccion').value,
            lineaId: document.getElementById('idLinea').value,
            familiaId: document.getElementById('idFamilia').value,
            subfamiliaId: document.getElementById('idSubfamilia').value
        };

        // Construir query params
        const queryParams = new URLSearchParams();
        // Solo añadir los filtros que tengan valor
        Object.entries(filtros).forEach(([key, value]) => {
            if (value) queryParams.append(key, value);
        });

        // Mostrar indicador de carga
        const productGrid = document.getElementById('productGrid');
        productGrid.innerHTML = '<div class="loading">Cargando productos...</div>';

        // Realizar la petición
        const response = await fetch(`obtenerProductos.php?${queryParams.toString()}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const productos = await response.json();

        // Limpiar el grid
        productGrid.innerHTML = '';

        // Verificar si hay productos
        if (!productos || productos.length === 0) {
            productGrid.innerHTML = `
                <div class="no-products">
                    <p>No se encontraron productos para los filtros seleccionados.</p>
                </div>
            `;
            return;
        }

        // Crear las tarjetas de productos
        productos.forEach(producto => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            
            // Sanitizar los datos para prevenir XSS
            const sanitizedProducto = {
                imagen: producto.imagen || '/placeholder-image.jpg',
                nombre: producto.nombre || 'Producto sin nombre',
                descripcion: producto.descripcion || 'Sin descripción',
                cantidad: producto.cantidad || 0,
                precio: Number(producto.precio).toFixed(2) || '0.00',
                id: producto.id || ''
            };

            productCard.innerHTML = `
                <img src="${sanitizedProducto.imagen}" 
                     alt="${sanitizedProducto.nombre}"
                     onerror="this.src='/placeholder-image.jpg'">
                <h3>${sanitizedProducto.nombre}</h3>
                <p>${sanitizedProducto.descripcion}</p>
                <p>Cantidad disponible: ${sanitizedProducto.cantidad}</p>
                <p class="product-price">$${sanitizedProducto.precio}</p>
                <button class="agregar-carrito" 
                        data-id="${sanitizedProducto.id}"
                        ${sanitizedProducto.cantidad <= 0 ? 'disabled' : ''}>
                    ${sanitizedProducto.cantidad <= 0 ? 'Sin stock' : 'Agregar al carrito'}
                </button>
            `;

            // Agregar evento al botón
            const btnAgregar = productCard.querySelector('.agregar-carrito');
            btnAgregar.addEventListener('click', () => {
                agregarAlCarrito(sanitizedProducto.id);
            });

            productGrid.appendChild(productCard);
        });

    } catch (error) {
        console.error("Error al cargar productos:", error);
        document.getElementById('productGrid').innerHTML = `
        `;
    }
}

// Agregar listeners para los filtros
document.addEventListener('DOMContentLoaded', () => {
    const filtros = ['idArea', 'idSeccion', 'idLinea', 'idFamilia', 'idSubfamilia'];
    
    filtros.forEach(filtroId => {
        document.getElementById(filtroId)?.addEventListener('change', cargarProductos);
    });

    // Cargar productos iniciales
    cargarProductos();
});
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