<?php
// ===================================
// 1. LÓGICA DE INSERCIÓN DE DATOS
// ===================================

// Configuración de conexión (¡usa tus credenciales de InfinityFree!)
$servername = "sql213.infinityfree.com";
$username = "if0_40297195";
$password = "chuchoana2005";
$dbname = "if0_40297195_Usuario";

$mensaje = ""; // Variable para mostrar mensajes al usuario

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $mensaje = "Error de conexión: " . $conn->connect_error;
    } else {
        // Recoger y limpiar (sanitizar) los datos del formulario
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $apPaterno = $conn->real_escape_string($_POST['apellido_paterno']);
        $apMaterno = $conn->real_escape_string($_POST['apellido_materno']);
        $correo = $conn->real_escape_string($_POST['correo']);
        $telefono = $conn->real_escape_string($_POST['telefono']);

        // Consulta de inserción (INSERT)
        $sql_insert = "INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, correo, telefono) 
                       VALUES ('$nombre', '$apPaterno', '$apMaterno', '$correo', '$telefono')";

        if ($conn->query($sql_insert) === TRUE) {
            $mensaje = "✅ Nuevo usuario agregado con éxito.";
            // Redirigir al índice para ver la tabla actualizada después de 2 segundos
            header("refresh:2;url=index.php"); 
        } else {
            $mensaje = "❌ Error al agregar usuario: " . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Usuario</title>
    <style>
        /* ================================
           Estilo base tipo macOS
        ================================= */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #f5f5f7, #e9ebee);
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Contenedor principal con efecto “glass” */
        .container {
            max-width: 500px;
            margin: 60px auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #1c1c1e;
            font-weight: 600;
        }

        /* ================================
           Etiquetas e inputs
        ================================= */
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border: 1px solid #d1d1d6;
            border-radius: 8px;
            font-size: 15px;
            background-color: rgba(255, 255, 255, 0.8);
            transition: all 0.2s ease-in-out;
            box-sizing: border-box;
        }

        /* Efecto suave al enfocar */
        input:focus {
            border-color: #007aff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0,122,255,0.2);
            background-color: white;
        }

        /* ================================
           Botón principal tipo macOS
        ================================= */
        .btn-submit {
            width: 100%;
            background: linear-gradient(145deg, #007aff, #0062d6);
            color: white;
            padding: 12px;
            margin-top: 25px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 10px rgba(0,122,255,0.3);
        }

        .btn-submit:hover {
            background: linear-gradient(145deg, #3395ff, #007aff);
            transform: translateY(-2px);
        }

        /* ================================
           Mensajes tipo notificación
        ================================= */
        .mensaje-exito, .mensaje-error {
            margin-top: 20px;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .mensaje-exito {
            background-color: #e8f9ed;
            color: #1b7a2e;
            border: 1px solid #bde5c8;
        }

        .mensaje-error {
            background-color: #fdecec;
            color: #c30000;
            border: 1px solid #f5c2c2;
        }

        /* ================================
           Botón de regreso
        ================================= */
        .btn-back {
            text-align: center;
            margin-top: 20px;
        }

        .btn-back a {
            color: #007aff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease-in-out;
        }

        .btn-back a:hover {
            color: #0051a8;
        }

        /* ================================
           Responsividad
        ================================= */
        @media (max-width: 600px) {
            .container {
                margin: 30px 15px;
                padding: 25px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Agregar Nuevo Usuario</h1>

        <?php if (!empty($mensaje)): ?>
            <div class="<?php echo (strpos($mensaje, '✅') !== false) ? 'mensaje-exito' : 'mensaje-error'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form action="formulario_registro.php" method="POST">
            
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido_paterno">Apellido Paterno:</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" required>

            <label for="apellido_materno">Apellido Materno:</label>
            <input type="text" id="apellido_materno" name="apellido_materno">

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono">

            <button type="submit" class="btn-submit">Registrar Usuario</button>
        </form>
        
        <div class="btn-back">
             <a href="index.php">← Volver al listado</a>
        </div>
    </div>
    
</body>
</html>
