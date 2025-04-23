
<?php
require_once 'Conexion/bd.php'; // Ajusta la ruta según tu estructura de directorios

// Conexión a la base de datos
$conexionBD = new ConexionBD();
    $conn = $conexionBD->getConexionBD();

// Consulta para obtener los valores de la tabla SEXO
$sqlSexo = "SELECT ID_SEXO, NOMBRE FROM SEXO";
$stmtSexo = $conn->prepare($sqlSexo);
$stmtSexo->execute();
$sexos = $stmtSexo->fetchAll(PDO::FETCH_ASSOC); // Obtener todos los resultados
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="registro.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
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
</head>
<body>
    <div class="register-container">
        <h2>Registrar Usuario</h2>
        <form id="registerForm" action="Controladores/registro.php" method="POST">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required>

            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" required>

            <label for="apellidoPaterno">Apellido Paterno:</label>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno" required>

            <label for="apellidoMaterno">Apellido Materno:</label>
            <input type="text" id="apellidoMaterno" name="apellidoMaterno">

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
            <input type="text" id="celular" name="celular">

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

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

            <label for="clave">Contraseña:</label>
            <input type="password" id="clave" name="clave" required>

            <label for="confirmarClave">Confirmar Contraseña:</label>
            <div class="input-container">
                <input type="password" id="confirmarClave" name="confirmarClave" required>
                <span id="password-icon" class="icon"></span> 
            </div>

            <span id="password-error" class="error-message"></span>

            <button type="submit">Registrar</button>
            <button type="button" onclick="window.location.href='index.php'">Cancelar</button>
        </form>
    </div>

     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/es.js"></script>

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
</body>
</html>
