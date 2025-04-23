<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        /* Estilos generales */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #f5d20e, #0051D4);
    background-size: 200% 200%;
    animation: gradientBackground 10s ease infinite;
}

/* Contenedor principal del login */
.login-container {
    display: flex;
    width: 900px;
    height: 500px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 25px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    backdrop-filter: blur(10px);
    border: 5px solid white;
}

/* Sección izquierda */
.login-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #003a8d, #2467d3);
    padding: 20px;
    border-top-left-radius: 25px;
    border-bottom-left-radius: 25px;
}

.welcome-image {
    width: 140px;
    height: 140px;
    margin-bottom: 20px;
    border-radius: 50%;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    animation: floatImage 2s ease-in-out infinite;
}

h2 {
    color: white;
    font-size: 30px;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

p {
    color: white;
    font-size: 16px;
    text-align: center;
}

/* Sección derecha */
.login-right {
    flex: 1;
    padding: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #efc313, #feda00);
    box-shadow: inset 0 0 50px rgba(255, 255, 255, 0.2);
    border-top-right-radius: 25px;
    border-bottom-right-radius: 25px;
}

/* Botones */
.button-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}

.login-btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #003f9c, #2467d3);
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.login-btn:hover {
    transform: translateY(-2px);
    background-color: #0051D4;
}

/* Estilo botón gerente */
.login-btn.gerente {
    background: linear-gradient(135deg, #c71f1f, #f54b4b);
}

.login-btn.gerente:hover {
    background: #800404;
}

/* Animaciones */
@keyframes gradientBackground {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes floatImage {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

    </style>
</head>
<body>
    <div class="login-container">
        <!-- Sección izquierda -->
        <div class="login-left">
            <img src="imagenes/logo.jpg" alt="Logo" class="welcome-image">
            <h2>Bienvenido</h2>
            <p>Elige cómo deseas ingresar:</p>
        </div>
        <!-- Sección derecha -->
        <div class="login-right">
            <h3>Acceso</h3>
            <div class="button-container">
                <button onclick="window.location.href='Vista/Gerente/Principal.php'" class="login-btn">Administrador</button>
                <button onclick="location.href='Vista/Cliente/Principal.php'" class="login-btn gerente">Cliente</button>
            </div>
        </div>
    </div>
</body>
</html>
