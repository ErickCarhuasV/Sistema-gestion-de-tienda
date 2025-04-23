<?php
require_once 'productoDao.php'; // Incluir el DAO de producto
$productoDAO = new ProductoDAO();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];
    $marca = $_POST['marca'];
    $subfamilia = $_POST['subfamilia'];
    $cantidad = $_POST['cantidad'];
    $cantidad = $_POST['familia'];
    $cantidad = $_POST['linea'];
    $cantidad = $_POST['seccion'];
    $cantidad = $_POST['area'];


    // Llamar al método para agregar el producto
    $productoDAO->agregarProducto($nombre, $descripcion, $precio, $tipo, $marca, $subfamilia, $cantidad,$familia,$linea,$seccion,$area);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prodc2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Lista de Productos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="sidebar">
        <h1>Panel de Control</h1>
        <ul class="sidebar-nav">
            <li><a href="Principal.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="Usuario.php"><i class="fa-solid fa-user"></i>Lista de Usuario</a></li>
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
        <center><h2>Lista de Productos</h2></center>
        
        <!-- Campo de búsqueda -->
        <div class="search-container">
            <input type="text" id="buscarProducto" placeholder="Buscar producto..." onkeyup="filtrarProductos()">
        </div>

        <div class="table-responsive">
            <table id="productTable">
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Subfamilia</th>
                        <th>Familia</th>
                        <th>Linea</th>
                        <th>Seccion</th>
                        <th>Area</th>
                        <th>Otras Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se cargarán los productos dinámicamente -->
                </tbody>
            </table>
        </div>

        <!-- Botón de "Agregar Producto" debajo de la tabla -->
        <button type="button" id="btn-agregar"><a href="agregar_producto.php">Agregar Producto</a></button>
    </div>

    <script>
        // Cargar productos al cargar la página
        $(document).ready(function() {
            loadProducts();
        });

        // Función para cargar productos
        function loadProducts() {
            $.ajax({
                url: 'listar_productos.php',
                method: 'GET',
                success: function(data) {
                    $('#productTable tbody').html(data);
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar los productos:", error);
                    alert('Hubo un problema al cargar los productos.');
                }
            });
        }

        // Manejar el envío del formulario para agregar productos
        $('#addProductForm').on('submit', function(e) {
            e.preventDefault(); // Evitar el envío normal del formulario

            let formData = $(this).serialize(); // Serializar los datos del formulario

            $.ajax({
                url: 'agregar_producto.php', // Archivo que procesa la inserción
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert(response); // Mostrar mensaje de éxito o error
                    loadProducts(); // Recargar la lista de productos
                    $('#addProductForm')[0].reset(); // Limpiar el formulario
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    alert('Hubo un problema al agregar el producto. Por favor, intenta de nuevo.');
                }
            });
        });

        function editarProducto(id) {
            window.location.href = `editar_producto.php?id=${id}`;
        }

        // Función para filtrar productos
        function filtrarProductos() {
            let input = document.getElementById("buscarProducto");
            let filter = input.value.toLowerCase();
            let table = document.getElementById("productTable");
            let tr = table.getElementsByTagName("tr");

            // Itera sobre las filas de la tabla (saltando la fila del encabezado)
            for (let i = 1; i < tr.length; i++) {
                let match = false;
                let tdArray = tr[i].getElementsByTagName("td");
                
                // Itera sobre las celdas de cada fila
                for (let j = 0; j < tdArray.length; j++) {
                    let td = tdArray[j];
                    if (td) {
                        let txtValue = td.textContent || td.innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }
                
                if (match) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        function eliminarProducto(idProducto) {
    if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
        fetch('eliminarProducto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + encodeURIComponent(idProducto)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    }
}

    </script>
</body>
</html>
