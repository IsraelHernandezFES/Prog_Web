<?php
// Inicia la sesión de PHP. Es crucial para mantener al usuario logeado.
session_start();

$error = '';

// Credenciales ÚNICAS y personales (¡Cámbialas!)
$usuario_valido = 'ingenierocomputacion'; // Por ejemplo: 'ingenierocomputacion'
$password_valido = 'shinediscotec'; // Por ejemplo: 'JavaFullStack2025'

// Verificar si el formulario de login fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_ingresado = $_POST['usuario'] ?? '';
    $password_ingresada = $_POST['contrasenia'] ?? '';

    // Comparar credenciales
    if ($usuario_ingresado === $usuario_valido && $password_ingresada === $password_valido) {
        // Credenciales correctas: Marcar como logeado y redirigir a la tabla
        $_SESSION['loggedin'] = true;
        $_SESSION['usuario'] = $usuario_ingresado;
        
        // Redirigir a la página principal de gestión
        header('Location:gestor.php');
        exit;
    } else {
        // Credenciales incorrectas
        $error = 'Nombre de usuario o contraseña incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Gestión</title>
    <style>
        /* ===========================
           Estilos Generales (Fondo con Imagen y Tema Oscuro)
        ============================ */
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
            /* Fondo Minimalista con Plantas (Reemplaza la URL con una imagen real o un placeholder) */
            background-image: url('https://source.unsplash.com/random/1920x1080/?minimalist,dark,plant'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* ===========================
           Contenedor de Login (Efecto 'Glassmorphism' Oscuro)
        ============================ */
        .login-container { 
            background: rgba(45, 45, 50, 0.85); /* Fondo oscuro semi-transparente */
            backdrop-filter: blur(25px); /* Efecto de cristal esmerilado, clave macOS */
            -webkit-backdrop-filter: blur(25px);
            padding: 35px; 
            border-radius: 18px; 
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4); /* Sombra intensa para resaltar */
            width: 300px; 
            border: 1px solid rgba(255, 255, 255, 0.1); /* Borde sutil */
            color: #dcdcdc; /* Texto claro */
        }

        h1 { 
            text-align: center; 
            color: white; 
            margin-bottom: 25px; 
            font-weight: 500;
        }

        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 400; 
            color: #dcdcdc;
            font-size: 15px;
        }

        /* Campos de Input */
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 20px; 
            border: 1px solid #4a4a4f; /* Borde oscuro */
            border-radius: 8px; 
            box-sizing: border-box;
            background-color: #38383d; /* Fondo de campo ligeramente más claro que el contenedor */
            color: white;
            font-size: 15px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007aff; /* Azul de enfoque de Apple */
            box-shadow: 0 0 0 2px rgba(0, 122, 255, 0.5); 
            outline: none;
            background-color: #404045;
        }

        /* Botón de Submit */
        button { 
            width: 100%; 
            padding: 12px; 
            background-color: #007aff; /* Azul primario */
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.2s, transform 0.1s;
        }

        button:hover { 
            background-color: #0056b3; 
            transform: translateY(-1px);
        }

        /* Mensaje de Error */
        .error { 
            color: #ff453a; /* Rojo tipo iOS */
            text-align: center; 
            margin-bottom: 15px; 
            font-weight: 500;
            background-color: rgba(255, 69, 58, 0.1);
            padding: 8px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Inicio de Sesión</h1>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="contrasenia">Contraseña:</label>
        <input type="password" id="contrasenia" name="contrasenia" required>

        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>