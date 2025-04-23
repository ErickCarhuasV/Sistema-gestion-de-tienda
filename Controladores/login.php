<?php
require_once '../Modelo/UsuarioBean.php'; 
require_once '../Modelo/UsuarioDao.php'; 
require_once '../Conexion/bd.php'; 

// Establecer la conexión
$conn = (new ConexionBD())->getConexionBD(); 
$usuarioDao = new UsuarioDao($conn); 

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['CORREOELECTRONICO']) && isset($_POST['clave'])) {
        $correoElectronico = $_POST['CORREOELECTRONICO']; 
        $clave = $_POST['clave']; 

        // Crea el objeto UsuarioBean
        $usuario = new UsuarioBean($correoElectronico, $clave, null); 

        // Verifica la autenticación
        if ($usuarioDao->autenticar($usuario)) {
            
            // Obtén el perfil del usuario
            $idPerfil = $usuarioDao->obtenerPerfil($usuario->getCorreoElectronico());
            // Establecer el perfil en el objeto usuario
            $usuario->setIdPerfil($idPerfil);

            // Almacenar información en la sesión
            session_start();
            $_SESSION['usuario'] = $usuario; // Guardar el objeto UsuarioBean en la sesión

            // Redirige según el perfil
            if ($idPerfil == 1) {
                header('Location: ../perfiles.php');
            } elseif ($idPerfil == 2) {
                header('Location: administradorcito.php');
            } else {
                header('Location: ../Vista/Cliente/Principal.php');
            }
            exit();
        } else {
            echo "<script>
                    alert('Correo o contraseña incorrectos.');
                    window.location.href='../index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Por favor, complete todos los campos.');
                window.location.href='../index.php';
              </script>";
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>
