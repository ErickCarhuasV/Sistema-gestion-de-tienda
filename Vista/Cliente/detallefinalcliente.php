<?php
// Incluir la clase de conexión y el DAO
require_once '../../Conexion/bd.php';  
require_once '../../Modelo/UsuarioDao.php';  
require_once '../../Modelo/UsuarioBean.php';  

// Crear una instancia de la conexión usando ConexionBD
$conexionBD = new ConexionBD();
$conn = $conexionBD->getConexionBD();  

// Crear una instancia de UsuarioDAO pasando la conexión
$usuarioDao = new UsuarioDAO($conn);

// Verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");  
    exit();
}

// Obtener el usuario desde la sesión
$usuario = $_SESSION['usuario'];  

// Obtener los datos del usuario desde la base de datos
$datosUsuario = $usuarioDao->obtenerUsuarioPorDNI($usuario->getCorreoElectronico());
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Usuario</title>
    <link rel="stylesheet" href="confi.css">
</head>
<body>
    <div class="container">
        <?php if ($datosUsuario): ?>
            <div class="user-details-container">
                <h3>Detalles del Usuario</h3>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($datosUsuario['NOMBRES']); ?></p>
                <p><strong>Apellido Paterno:</strong> <?php echo htmlspecialchars($datosUsuario['APELLIDOPATERNO']); ?></p>
                <p><strong>Apellido Materno:</strong> <?php echo htmlspecialchars($datosUsuario['APELLIDOMATERNO']); ?></p>
                <p><strong>Celular:</strong> <?php echo htmlspecialchars($datosUsuario['CELULAR']); ?></p>
                <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($datosUsuario['CORREOELECTRONICO']); ?></p>
            </div>
        <?php else: ?>
            <div class="user-details-container">
                <p>No se encontraron los datos del usuario.</p>
            </div>
        <?php endif; ?>

        <div class="container">
    <h3>Editar Datos del Usuario</h3>
    <form action="procesar_edicion.php" method="POST" class="form-container">
        <input type="hidden" name="idUsuario" value="<?php echo htmlspecialchars($datosUsuario['IDUSUARIO']); ?>">

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($datosUsuario['NOMBRES']); ?>" required>
        </div>

        <div class="form-group">
            <label for="apellidoPaterno">Apellido Paterno:</label>
            <input type="text" name="apellidoPaterno" value="<?php echo htmlspecialchars($datosUsuario['APELLIDOPATERNO']); ?>" required>
        </div>

        <div class="form-group">
            <label for="apellidoMaterno">Apellido Materno:</label>
            <input type="text" name="apellidoMaterno" value="<?php echo htmlspecialchars($datosUsuario['APELLIDOMATERNO']); ?>" required>
        </div>

        <div class="form-group">
            <label for="celular">Celular:</label>
            <input type="text" name="celular" value="<?php echo htmlspecialchars($datosUsuario['CELULAR']); ?>" required>
        </div>

        <div class="form-group">
            <label for="correoElectronico">Correo Electrónico:</label>
            <input type="email" name="correoElectronico" value="<?php echo htmlspecialchars($datosUsuario['CORREOELECTRONICO']); ?>" required>
        </div>

        <div class="form-group">
            <label for="clave">Nueva Contraseña:</label>
            <input type="password" name="clave">
            <small>Dejar vacío si no desea cambiarla</small>
        </div>

        <div class="form-group">
            <label for="confirmarClave">Confirmar Contraseña:</label>
            <input type="password" name="confirmarClave">
            <small>Debe coincidir con la nueva contraseña</small>
        </div>

        <div class="form-actions">
    <button type="submit" class="btn-submit">Actualizar</button>
    <a href="Principal.php" class="btn-cancel">Cancelar</a>
</div>
<div class="form-actions">
    <a href="procesar_pago.php" class="btn-details">Volver a detalles de compra</a>
</div>
    </form>
</div>
    <script>
        function validarFormulario() {
            const clave = document.getElementById('clave').value;
            const confirmarClave = document.getElementById('confirmarClave').value;

            if (clave !== confirmarClave) {
                alert('Las contraseñas no coinciden. Por favor, intente de nuevo.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
