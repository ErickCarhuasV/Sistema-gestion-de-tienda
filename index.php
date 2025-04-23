<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login FlashDelivery</title>
    <link rel="stylesheet" href="estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"> <!-- Fuente Poppins aplicada -->
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <img src="imagenes/logo.jpg" alt="Imagen de bienvenida" class="welcome-image">
            <h2>¡Bienvenido!</h2>
            <p>Por favor, ingrese sus datos para continuar.</p>
        </div>
        <div class="login-right">
            <h3>Iniciar Sesión</h3>
            <form id="loginForm" method="POST" action="Controladores/login.php">
                <label for="CORREOELECTRONICO">CORREO*</label>
                <input type="text" name="CORREOELECTRONICO" required placeholder="Ingrese su CORREO">

                <label for="clave">Contraseña*</label>
                <input type="password" name="clave" required placeholder="Ingrese su contraseña">       

                <div id="password-error" class="error"></div>

                <button type="submit">Iniciar Sesión</button>
            </form>

            <div class="register-container">
                <p>¿Eres nuevo por aquí?</p>
                <button class="register-btn" onclick="window.location.href='registro.php'">¡REGÍSTRATE!</button>
            </div>

            <div class="social-media">
                <p>Síguenos en nuestras redes sociales:</p>
                <a href="https://www.facebook.com/TiendasMassPeru/?locale=es_LA" target="_blank">Facebook</a>
                <a href="https://www.instagram.com/tiendasmassperu/?hl=es" target="_blank">Instagram</a>
                <a href="https://www.tiktok.com/@tiendasmassperu?lang=es" target="_blank">TikTok</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            const password = document.querySelector('input[name="clave"]').value;
            const passwordError = document.getElementById('password-error');

            const passwordRegex = /^(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/; 


            if (!passwordRegex.test(password)) {
                event.preventDefault(); 
                passwordError.style.display = 'block';
                passwordError.textContent = 'La contraseña debe tener al menos 8 caracteres y un símbolo.';
            } else {
                passwordError.style.display = 'none';
            }
        });
    </script>
</body>
</html>