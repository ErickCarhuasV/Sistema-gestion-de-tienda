<?php
require_once '../../Conexion/bd.php';
require_once 'AgregarBeans.php';
require_once 'AgregarDao.php';
require_once 'AgregarPerBeans.php';
require_once 'AgregarPerDao.php';

$conexionBD = new ConexionBD();
$conexion = $conexionBD->getConexionBD();
$usuarioDao = new AgregarDao($conexion);
$perfilDao = new AgregarPerDao($conexion);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new AgregarBeans($_POST);
    $estadoRegistro = 1;

    try {
        if ($usuarioDao->agregarUsuario($usuario, $estadoRegistro)) {
            $query = "SELECT USUARIO_SEQ.CURRVAL FROM dual";
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            $idUsuario = $stmt->fetchColumn();
            $idPerfil = $_POST['tipoUsuario'] === 'Administrador' ? 2 : 3;

            $perfil = new RegistroBean([
                'idUsuario' => $idUsuario,
                'idPerfil' => $idPerfil
            ]);

            if ($perfilDao->agregarPerfil($perfil)) {
                echo "<script>alert('Usuario agregado correctamente.'); window.location.href='Usuario.php';</script>";
                exit;
            } else {
                echo "Usuario agregado, pero error al agregar perfil.";
            }
        } else {
            echo "Error al agregar el usuario.";
        }
    } catch (Exception $e) {
        echo "Error al agregar el usuario: " . $e->getMessage();
    }
}

// Consulta para obtener los valores de la tabla SEXO
$sqlSexo = "SELECT ID_SEXO, NOMBRE FROM SEXO";
$stmtSexo = $conexion->prepare($sqlSexo); // Cambiar $conn por $conexion
$stmtSexo->execute();
$sexos = $stmtSexo->fetchAll(PDO::FETCH_ASSOC); // Obtener todos los resultados

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../registro.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <center><title>Agregar Usuario</title><center/>
    <style>
        .status-icon {
            font-weight: bold;
            font-size: 1.2em;
            display: inline;
        }
        .valid { color: green; }
        .invalid { color: red; }
    </style>
      <style>
        .icon-success {
            color: green;
            font-weight: bold;
        }

        .icon-error {
            color: red;
            font-weight: bold;
        }

        .icon {
            margin-left: 10px;
        }
        
        /* Estilos para los selectores de fecha */
        .fecha-nacimiento-container {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .fecha-input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .flatpickr-input {
            background: white !important;
        }
    </style>
    <script>
        function validatePassword() {
            const password = document.getElementById('clave').value;
            const confirmPassword = document.getElementById('confirmClave').value;
            const statusIcon = document.getElementById('statusIcon');

            if (confirmPassword === "") {
                statusIcon.textContent = "";
            } else if (password === confirmPassword) {
                statusIcon.textContent = "✔";
                statusIcon.className = "status-icon valid";
            } else {
                statusIcon.textContent = "✖";
                statusIcon.className = "status-icon invalid";
            }
        }
    </script>
</head>
<body>
<div class="register-container">
    <h1>Agregar Usuario</h1>
    <form method="POST" action="agregar.php">
        <label for="dni">DNI:</label>
        <input type="text" name="dni" required><br>

        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" required><br>

        <label for="apellidoPaterno">Apellido Paterno:</label>
        <input type="text" name="apellidoPaterno" required><br>

        <label for="apellidoMaterno">Apellido Materno:</label>
        <input type="text" name="apellidoMaterno"><br>

        <!-- Campo Sexo -->
        <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" required>
                <option value="">Seleccione el sexo</option>
                <?php foreach ($sexos as $sexo): ?>
                    <option value="<?= htmlspecialchars($sexo['ID_SEXO']) ?>">
                        <?= htmlspecialchars($sexo['NOMBRE']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

        <label for="celular">Celular:</label>
        <input type="text" name="celular"><br>

        <label for="correoElectronico">Correo Electrónico:</label>
        <input type="email" name="correoElectronico" required><br>

        <form action="agregar.php" method="post">
        
        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
<input type="text" id="fechaNacimiento" name="fechaNacimiento" class="fecha-input" required placeholder="Selecciona tu fecha">

<label for="region">Región:</label>
            <select id="region" name="regionId" required>
    <option value="">Seleccione una región</option>
    <?php 
    // Consulta para obtener las regiones
    $sqlRegion = "SELECT ID_REGION, NOMBRE_REGION FROM REGION";
    $stmtRegion = $conn->prepare($sqlRegion);
    $stmtRegion->execute();
    $regiones = $stmtRegion->fetchAll(PDO::FETCH_ASSOC);

    // Generar las opciones dinámicamente
    foreach ($regiones as $region): ?>
        <option value="<?= htmlspecialchars($region['ID_REGION']) ?>">
            <?= htmlspecialchars($region['NOMBRE_REGION']) ?>
        </option>
    <?php endforeach; ?>
</select>

<label for="provincia">Provincia:</label>
<select id="provincia" name="provinciaId" required>
    <option value="">Seleccione una provincia</option>
    <?php 
    // Consulta para obtener las provincias
    $sqlProvincia = "SELECT ID_PROVINCIA, NOMBRE_PROVINCIA FROM PROVINCIA";
    $stmtProvincia = $conn->prepare($sqlProvincia);
    $stmtProvincia->execute();
    $provincias = $stmtProvincia->fetchAll(PDO::FETCH_ASSOC);

    // Generar las opciones dinámicamente
    foreach ($provincias as $provincia): ?>
        <option value="<?= htmlspecialchars($provincia['ID_PROVINCIA']) ?>">
            <?= htmlspecialchars($provincia['NOMBRE_PROVINCIA']) ?>
        </option>
    <?php endforeach; ?>
</select>

<label for="distrito">Distrito:</label>
<select id="distrito" name="distritoId" required>
    <option value="">Seleccione un distrito</option>
    <?php 
    $sqlDistrito = "SELECT ID_DISTRITO, NOMBRE_DISTRITO FROM DISTRITO";
    $stmtDistrito = $conn->prepare($sqlDistrito);
    $stmtDistrito->execute();
    $distritos = $stmtDistrito->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($distritos as $distrito): ?>
        <option value="<?= htmlspecialchars($distrito['ID_DISTRITO']) ?>">
            <?= htmlspecialchars($distrito['NOMBRE_DISTRITO']) ?>
        </option>
    <?php endforeach; ?>
</select>

        <label for="clave">Clave:</label>
        <input type="password" id="clave" name="clave" required oninput="validatePassword()"><br>

        <label for="confirmClave">Confirmar Clave:</label>
        <input type="password" id="confirmClave" name="confirmClave" required oninput="validatePassword()">
        <span id="statusIcon" class="status-icon"></span><br>
        <label for="tipoUsuario">Tipo de Usuario:</label>
<select name="tipoUsuario" required>
    <option value="">Seleccione un tipo</option>
    <option value="Administrador">Administrador</option>
    <option value="Cliente">Cliente</option>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/es.js"></script>
</select><br>
<script>
        // Configuración para el selector de fecha
        flatpickr("#fechaNacimiento", {
            locale: 'es',
            dateFormat: "Y-m-d", // Formato compatible con SQL
        });

        const passwordField = document.getElementById('clave');
        const confirmPasswordField = document.getElementById('confirmarClave');
        const passwordError = document.getElementById('password-error');
        const passwordIcon = document.getElementById('password-icon');

        confirmPasswordField.addEventListener('input', function () {
            const password = passwordField.value;
            const confirmarPassword = confirmPasswordField.value;

            // Verificar si las contraseñas coinciden
            if (password !== confirmarPassword) {
                passwordIcon.textContent = '❌'; // Mostrar X
                passwordIcon.className = 'icon icon-error';
            } else if (password === confirmarPassword && password.length > 0) {
                passwordIcon.textContent = '✔️'; 
                passwordIcon.className = 'icon icon-success';
            } else {
                passwordIcon.textContent = ''; 
            }
        });

        document.getElementById('registerForm').addEventListener('submit', function (event) {
            const password = passwordField.value;
            const confirmarPassword = confirmPasswordField.value;

            const passwordRegex = /^(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

            // Verificar que la contraseña cumple con el patrón
            if (!passwordRegex.test(password)) {
                event.preventDefault(); 
                passwordError.style.display = 'block';
                passwordError.textContent = 'La contraseña debe tener al menos 8 caracteres y un símbolo.';
                return; // Salir de la función
            }

            // Verificar que ambas contraseñas sean iguales antes de enviar el formulario
            if (password !== confirmarPassword) {
                event.preventDefault(); 
                passwordError.style.display = 'block';
                passwordError.textContent = 'Las contraseñas no coinciden.';
            } else {
                passwordError.style.display = 'none';
            }
        });
        document.getElementById('regionId').addEventListener('change', function () {
    const regionId = this.value;
    const provinciaSelect = document.getElementById('provinciaId');
    const distritoSelect = document.getElementById('distritoId');

    // Limpiar las provincias y distritos previamente cargados
    provinciaSelect.innerHTML = '<option value="">Seleccione una provincia</option>';
    distritoSelect.innerHTML = '<option value="">Seleccione una provincia primero</option>';

    if (regionId) {
        fetch(`getProvincias.php?region=${regionId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar provincias');
                }
                return response.json();
            })
            .then(data => {
                // Verificar que los datos son correctos antes de cargar
                if (Array.isArray(data) && data.length > 0) {
                    // Cargar las provincias
                    data.forEach(provinciaId => {
                        provinciaSelect.innerHTML += `<option value="${provinciaId.ID_PROVINCIA}">${provinciaId.NOMBRE_PROVINCIA}</option>`;
                    });
                } else {
                    provinciaSelect.innerHTML = '<option value="">No se encontraron provincias</option>';
                }
            })
            .catch(error => {
                console.error(error);
                provinciaSelect.innerHTML = '<option value="">Error al cargar provincias</option>';
            });
    } else {
        // Si no se seleccionó región, limpiar provincias y distritos
        provinciaSelect.innerHTML = '<option value="">Seleccione una región primero</option>';
        distritoSelect.innerHTML = '<option value="">Seleccione una provincia primero</option>';
    }
});

document.getElementById('provinciaId').addEventListener('change', function () {
    const provinciaId = this.value;
    const distritoSelect = document.getElementById('distritoId');

    // Limpiar distritos previamente cargados
    distritoSelect.innerHTML = '<option value="">Seleccione un distrito</option>';

    if (provinciaId) {
        fetch(`getDistritos.php?provincia=${provinciaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar distritos');
                }
                return response.json();
            })
            .then(data => {
                // Verificar que los datos son correctos antes de cargar
                if (Array.isArray(data) && data.length > 0) {
                    // Cargar los distritos
                    data.forEach(distritoId => {
                        distritoSelect.innerHTML += `<option value="${distritoId.ID_DISTRITO}">${distritoId.NOMBRE_DISTRITO}</option>`;
                    });
                } else {
                    distritoSelect.innerHTML = '<option value="">No se encontraron distritos</option>';
                }
            })
            .catch(error => {
                console.error(error);
                distritoSelect.innerHTML = '<option value="">Error al cargar distritos</option>';
            });
    } else {
        // Si no se seleccionó provincia, limpiar distritos
        distritoSelect.innerHTML = '<option value="">Seleccione una provincia primero</option>';
    }
});
    </script>
        <div class="button-group">
    <input type="submit" value="Agregar Usuario">
    <button type="button" onclick="window.location.href='Usuario.php'">Cancelar</button>
    </div>
    </form>
    </div>
</body>
</html>
