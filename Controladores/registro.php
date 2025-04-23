<?php
session_start(); // Inicia la sesión
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Incluir el DAO y el Bean
include_once '../Modelo/RegistroBean.php';
include_once '../Modelo/RegistroDao.php';
include_once '../Conexion/bd.php'; 

try {
    // Crear una instancia de la clase ConexionBD
    $conexionBD = new ConexionBD();
    $conn = $conexionBD->getConexionBD(); 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validar datos de entrada
        $errors = [];
        $dni = trim($_POST['dni']);
        $nombres = trim($_POST['nombres']);
        $apellidoPaterno = trim($_POST['apellidoPaterno']);
        $apellidoMaterno = trim($_POST['apellidoMaterno']);
        $celular = trim($_POST['celular']);
        $correo = trim($_POST['correo']);
        $clave = $_POST['clave'];
        $fechaNacimiento = trim($_POST['fechaNacimiento']);
        $regionId = trim($_POST['regionId']);
        $provinciaId = trim($_POST['provinciaId']);
        $distritoId = trim($_POST['distritoId']);
        $sexo = isset($_POST['sexo']) ? intval($_POST['sexo']) : null;
        list($anio, $mes, $dia) = explode('-', $fechaNacimiento);

        // Validaciones simples
        if (empty($dni)) $errors[] = "El DNI es requerido.";
        if (empty($nombres)) $errors[] = "Los nombres son requeridos.";
        if (empty($apellidoPaterno)) $errors[] = "El apellido paterno es requerido.";
        if (empty($apellidoMaterno)) $errors[   ] = "El apellido materno es requerido."; 
        if (empty($celular)) $errors[] = "El celular es requerido."; 
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errors[] = "El correo no es válido.";
        if (strlen($clave) < 6) $errors[] = "La clave debe tener al menos 6 caracteres.";
        if (empty($anio) || empty($mes) || empty($dia)) $errors[] = "La fecha de nacimiento es requerida.";
        if (empty($sexo)) $errors[] = "El sexo es requerido.";
        // Validar región
        if (empty($regionId)) {$errors[] = "La región es requerida.";}
        // Validar provincia
        if (empty($provinciaId)) {$errors[] = "La provincia es requerida.";}
        // Validar distrito
        if (empty($distritoId)) {$errors[] = "El distrito es requerido.";}
        // Si hay errores, guardarlos en la sesión y redirigir
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST; 
            return false;
        }

        // Crear un nuevo objeto RegistroBean
        $usuario = new RegistroBean(); 
        $usuario->setDni($dni);
        $usuario->setNombres($nombres);
        $usuario->setApellidoPaterno($apellidoPaterno);
        $usuario->setApellidoMaterno($apellidoMaterno);
        $usuario->setCelular($celular);
        $usuario->setCorreoElectronico($correo);
        $usuario->setClave($clave); 
        $usuario->setFechaNacimiento($fechaNacimiento);
        $usuario->setSexo($sexo); // Asignar el sexo
        $usuario->setRegion($regionId); // Match form field names
        $usuario->setProvincia($provinciaId);
        $usuario->setDistrito($distritoId);

        // Crear una instancia de RegistroDAO y registrar el usuario
        $registroDao = new RegistroDao($conn);
        $tipoUsuario = 2; 

        if ($registroDao->registrarUsuario($usuario)) {
            $_SESSION['success'] = "Usuario registrado exitosamente";
            header("Location: ../index.php");
            exit;
        } else {
            throw new Exception("Error al registrar usuario");
        }
    } else {
        // Guardar mensaje de error en la sesión
        $_SESSION['error_message'] = "Error al registrar usuario.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    echo "Error de conexión a la base de datos: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn = null;
    }
}
?>
