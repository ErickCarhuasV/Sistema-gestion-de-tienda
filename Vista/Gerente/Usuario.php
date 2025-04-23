<?php
// Asegúrate de incluir el archivo de conexión
require_once '../../Conexion/bd.php'; 
require_once 'UsuarioDao.php'; 

// Inicializa el arreglo de usuarios
$usuarios = [];

try {
    // Crear una instancia de la conexión
    $conexion = (new ConexionBD())->getConexionBD(); 
    $usuarioDao = new UsuarioDao($conexion); 
    $usuarios = $usuarioDao->obtenerUsuarios(); 
} catch (Exception $e) {
    error_log("Error al obtener usuarios: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="prodc2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function editarFila(fila) {
            const celdas = fila.getElementsByTagName("td");
            for (let i = 1; i < celdas.length - 1; i++) {
        const celda = celdas[i];
        const textoOriginal = celda.innerText;

        // Si es la columna de fecha de nacimiento (ajusta el índice según tu tabla)
        if (celda.dataset.field === "fechaNacimiento") {
            // Convertir el formato de fecha DD/MM/YY a YYYY-MM-DD para el input date
            const partesFecha = textoOriginal.split('/');
            if (partesFecha.length === 3) {
                let año = partesFecha[2];
                // Ajustar el año si es necesario (por ejemplo, si es de dos dígitos)
                if (año.length === 2) {
                    año = '20' + año;
                }
                const fechaFormateada = `${año}-${partesFecha[1].padStart(2, '0')}-${partesFecha[0].padStart(2, '0')}`;
                celda.innerHTML = `<input type="date" data-field="fechaNacimiento" value="${fechaFormateada}">`;
            } else {
                celda.innerHTML = `<input type="date" data-field="fechaNacimiento">`;
            }
            continue;
        }

        if (i === 1 || i === 8 || i === 10 || i === 11) continue;

        if (i === celdas.length - 2) {
            const estado = textoOriginal === 'Activo' ? '1' : '0';
            celda.innerHTML = `
                <select data-field="estadoRegistro">
                    <option value="1" ${estado === '1' ? 'selected' : ''}>Activo</option>
                    <option value="0" ${estado === '0' ? 'selected' : ''}>Inactivo</option>
                </select>`;
        } else {
            celda.innerHTML = `<input type="text" data-field="${celda.dataset.field}" value="${textoOriginal}">`;
        }
    }

    const accionCelda = celdas[celdas.length - 1];
    accionCelda.innerHTML = `<button class="save-button" onclick="guardarFila(this)">Guardar</button>`;
}

        function guardarFila(boton) {
            const fila = boton.parentElement.parentElement;
            const celdas = fila.getElementsByTagName("td");
            const datos = {};

            for (let i = 1; i < celdas.length - 1; i++) {
                const input = celdas[i].getElementsByTagName("input")[0] || celdas[i].getElementsByTagName("select")[0];
               if (input) {
            if (input.type === 'date') {
                // Convertir la fecha al formato que espera tu backend
                const fecha = new Date(input.value);
                const dia = fecha.getDate().toString().padStart(2, '0');
                const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
                const año = fecha.getFullYear().toString();
                datos[celdas[i].dataset.field] = `${dia}/${mes}/${año}`;
            } else {
                datos[celdas[i].dataset.field] = input.value;
            }
                }
            }

            const idUsuario = parseInt(fila.cells[0].innerText, 10);
    datos['idUsuario'] = idUsuario;
    datos['dni'] = fila.cells[1].innerText;
    datos['usuarioModificacion'] = 1;

    fetch('UsuarioController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Usuario actualizado exitosamente.');
            location.reload();
        } else {
            alert('Error al actualizar el usuario: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un problema al actualizar el usuario.');
    });
}

        function eliminarUsuario(idUsuario) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                fetch('UsuarioController.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ 'idUsuario': idUsuario, 'accion': 'eliminar' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuario eliminado exitosamente.');
                        location.reload();
                    } else {
                        alert('Error al eliminar el usuario: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al eliminar el usuario.');
                });
            }
        }

        function agregarUsuario() {
            window.location.href = 'agregar.php';
        }

        function buscarUsuarios() {
            const input = document.getElementById('search-input');
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll('table tr');

            rows.forEach((row, index) => {
                // Ignora la primera fila (encabezados)
                if (index === 0) {
                    row.style.display = ""; // Asegúrate de que los encabezados siempre estén visibles
                    return;
                }

                const nombreCelda = row.cells[2]; // Suponiendo que la columna de nombres es la tercera (índice 2)
                if (nombreCelda) {
                    const nombreTexto = nombreCelda.textContent || nombreCelda.innerText;
                    if (nombreTexto.toLowerCase().includes(filter)) {
                        row.style.display = ""; // Mostrar la fila
                    } else {
                        row.style.display = "none"; // Ocultar la fila
                    }
                }
            });
        }

        function cerrarSesion() {
            // Redirige a la página index.php
            window.location.href = '../../index.php';
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h1>Panel de Control</h1>
        <ul class="sidebar-nav">
            <li><a href="Principal.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="#"><i class="fa-solid fa-user"></i>Lista de Usuario</a></li>
            <li><a href="productos.php"><i class="fas fa-box"></i> Productos</a></li>
            <li><a href="#" onclick="cerrarSesion()">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="section">
            <h2>Lista de Usuarios</h2>

            <?php if (!empty($usuarios)): ?>
                <input type="text" id="search-input" class="search-bar" placeholder="Buscar por nombre..." oninput="buscarUsuarios()">
                <div class="table-responsive">
                <table>
    <tr>
        <th>ID</th>
        <th>DNI</th>
        <th>Nombres</th>
        <th>Apellido Paterno</th>
        <th>Apellido Materno</th>
        <th>Celular</th>
        <th>Correo Electrónico</th>
        <th>Fecha Nacimiento</th>
        <th>Estado Registro</th>
        <th>ID Dirección</th>
        <th>Dirección</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario->getIdUsuario(); ?></td>
            <td data-field="dni"><?= $usuario->getDni(); ?></td>
            <td data-field="nombres"><?= $usuario->getNombres(); ?></td>
            <td data-field="apellidoPaterno"><?= $usuario->getApellidoPaterno(); ?></td>
            <td data-field="apellidoMaterno"><?= $usuario->getApellidoMaterno(); ?></td>
            <td data-field="celular"><?= $usuario->getCelular(); ?></td>
            <td data-field="correoElectronico"><?= $usuario->getCorreoElectronico(); ?></td>
            <td data-field="fechaNacimiento"><?= $usuario->getFechaNacimiento(); ?></td>
            <td data-field="estadoRegistro"><?= $usuario->getEstadoRegistro() == 1 ? 'Activo' : 'Inactivo'; ?></td>
            <td data-field="idDireccion"><?= $usuario->getIdDireccion(); ?></td>
            <td data-field="direccion"><?= $usuario->getDireccion(); ?></td>
            <td>
                <button class="edit-button" onclick="editarFila(this.parentElement.parentElement)">✏️</button>
                <button class="delete-button" onclick="eliminarUsuario(<?= $usuario->getIdUsuario(); ?>)">❌</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
                </div>
            <?php else: ?>
                <p>No hay usuarios registrados.</p>
            <?php endif; ?>

            <button onclick="agregarUsuario()" class="add-button">Agregar Usuario</button>
        </div>
    </div>
</body>
</html>

