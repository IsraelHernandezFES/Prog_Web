<?php
// ===================================
// Bloque de Seguridad 
// ===================================
session_start();
// Si la sesión no está marcada como logeada, redirigir al login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// ===================================
// 1. LÓGICA DE CONEXIÓN Y CONSULTA
// ===================================

// ¡IMPORTANTE! Reemplaza estos valores con los que te dio InfinityFree
$servername = "sql213.infinityfree.com";
$username = "if0_40297195";
$password = "chuchoana2005";
$dbname = "if0_40297195_Usuario";

$conn = new mysqli($servername, $username, $password, $dbname);
// Manejar error de conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos - ¡SE AGREGA EL CAMPO 'id'!
$sql = "SELECT id, nombre, apellido_paterno, apellido_materno, correo, telefono FROM usuarios";
$result = $conn->query($sql);

// Array para guardar los datos
$usuarios = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        /* ===========================
           Estilo general tipo macOS
        ============================ */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #f5f5f7, #e9ebee);
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 40px;
            font-weight: 600;
            color: #1c1c1e;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            padding: 30px;
        }

        /* ===========================
           Tabla moderna tipo macOS
        ============================ */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
        }

        thead {
            background-color: rgba(240, 240, 240, 0.9);
        }

        th {
            padding: 14px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 1px solid #e0e0e0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            color: #333;
            vertical-align: middle;
        }

        tr:hover {
            background-color: rgba(0, 122, 255, 0.05);
            transition: background-color 0.2s ease-in-out;
        }

        /* ===========================
           Botones tipo macOS
        ============================ */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }

        .btn-add {
            background: green;
            color: white;
            box-shadow: 0 2px 5px rgba(0,122,255,0.4);
        }

        .btn-add:hover {
            background: linear-gradient(145deg, #3395ff, #007aff);
            transform: translateY(-2px);
        }

        .btn-edit {
            background-color: linear-gradient(145deg, #007aff, #0062d6);
            color: #1c1c1e;
        }

        .btn-edit:hover {
            background-color: #ffea80;
            transform: scale(1.05);
        }

        .btn-delete {
            background-color: #ff3b30;
            color: white;
        }

        .btn-delete:hover {
            background-color: #ff6b60;
            transform: scale(1.05);
        }

        .footer-actions {
            text-align: center;
            margin-top: 30px;
        }
        /* ===========================
           Estilos macOS para el Modal
        ============================ */

        .modal-overlay {
            /* Fondo oscuro y difuminado (Blur) */
            display: none; 
            position: fixed; 
            z-index: 2000; /* Asegurarse de que esté sobre todo */
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.25); /* Menos opaco que antes */
            backdrop-filter: blur(10px); /* Efecto de desenfoque, clave para el estilo macOS */
            -webkit-backdrop-filter: blur(10px); /* Soporte para navegadores WebKit */
            transition: backdrop-filter 0.3s;
        }

        .modal-content {
            /* Caja de Contenido (similar al material de Apple, con transparencia) */
            background: rgba(255, 255, 255, 0.9); /* Fondo blanco semi-transparente */
            margin: 8% auto; /* Centrar un poco más alto */
            padding: 30px; 
            border-radius: 18px; /* Bordes más redondeados */
            box-shadow: 0 15px 40px rgba(0,0,0,0.15), 0 0 1px rgba(0,0,0,0.1); /* Sombra suave y sutil */
            width: 90%; 
            max-width: 480px; 
            border: 1px solid rgba(255, 255, 255, 0.5); /* Borde interior sutil */
            animation: fadeInScale 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        .close-btn {
            color: #8e8e93; /* Gris claro de macOS */
            float: right; 
            font-size: 30px; 
            font-weight: 300; /* Fuente más ligera */
            cursor: pointer; 
            transition: color 0.2s;
            line-height: 1; /* Para alineación limpia */
        }

        .close-btn:hover, .close-btn:focus {
            color: #5a5a5a;
        }

        .modal-content h2 {
            color: #1c1c1e;
            font-weight: 600; /* Semi-bold */
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
            margin-bottom: 20px;
        }

        /* Campos de entrada con estilo iOS/macOS */
        .modal-content input[type="text"], 
        .modal-content input[type="email"] {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #d1d1d6; /* Borde sutil */
            border-radius: 9px; /* Bordes redondeados */
            box-sizing: border-box;
            font-size: 15px;
            background-color: #f9f9f9;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .modal-content input[type="text"]:focus, 
        .modal-content input[type="email"]:focus {
            border-color: #007aff; /* Azul de enfoque de Apple */
            box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.2); /* Sombra de enfoque azul */
            outline: none;
            background-color: #ffffff;
        }

        /* Animación para una entrada suave */
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* ===========================
           Responsividad
        ============================ */
        @media (max-width: 768px) {
            table {
                font-size: 13px;
            }

            th, td {
                padding: 10px;
            }

            .btn {
                padding: 6px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Gestión de Usuarios</h1>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ===================================
                // Impresión dinámica de filas
                // ===================================
                if (!empty($usuarios)):
                    foreach ($usuarios as $user): 
                        $userID = htmlspecialchars($user['id']);
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($user['apellido_paterno']); ?></td>
                        <td><?php echo htmlspecialchars($user['apellido_materno']); ?></td>
                        <td><?php echo htmlspecialchars($user['correo']); ?></td>
                        <td><?php echo htmlspecialchars($user['telefono']); ?></td>
                        <td>
							<a href="#" onclick="openEditModal(<?php echo $userID; ?>);" class="btn btn-edit">Editar</a>                            
                            <a href="eliminar.php?id=<?php echo $userID; ?>" 
                               class="btn btn-delete"
                               onclick="return confirm('¿Estás seguro de que quieres eliminar a <?php echo htmlspecialchars($user['nombre']); ?>?');">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php
                    endforeach;
                else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #777;">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer-actions">
            <a href="formulario_registro.php" class="btn btn-add">
                 Agregar Nuevo Usuario
            </a>
        </div>
    </div>
    <div id="editModal" class="modal-overlay" onclick="if(event.target.id === 'editModal') document.getElementById('editModal').style.display='none'">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
        <h2>✍Editar Usuario</h2>
        
        <div id="editMessage" style="text-align: center; margin-bottom: 15px; font-weight: bold;"></div>
        
        <form id="editForm" action="editar.php" method="POST">
            <input type="hidden" name="id" id="edit-id">
            
            <label for="edit-nombre">Nombre:</label>
            <input type="text" id="edit-nombre" name="nombre" required>

            <label for="edit-apellido_paterno">Apellido Paterno:</label>
            <input type="text" id="edit-apellido_paterno" name="apellido_paterno" required>

            <label for="edit-apellido_materno">Apellido Materno:</label>
            <input type="text" id="edit-apellido_materno" name="apellido_materno">

            <label for="edit-correo">Correo Electrónico:</label>
            <input type="email" id="edit-correo" name="correo" required>

            <label for="edit-telefono">Teléfono:</label>
            <input type="text" id="edit-telefono" name="telefono">

            <button type="submit" style="margin-top: 20px; width: 100%; padding: 12px; background-color: #007aff; color: white; border: none; border-radius: 9px; cursor: pointer; font-size: 16px; font-weight: 600; transition: background-color 0.2s;">
                Guardar Cambios
            </button>
        </form>
    </div>
</div>
</div>
<script>
    // ----------------------------------------------------
    // FUNCIÓN 1: Abrir el Modal y cargar datos (simulado)
    // ----------------------------------------------------
    function openEditModal(userId) {
        // En un proyecto real, harías una llamada AJAX GET aquí
        // a un endpoint como 'api/usuario.php?id=' + userId para obtener los datos.

        // Por ahora, como los datos ya están en la página, los buscamos directamente:
        const row = document.querySelector(`a[onclick*="${userId}"]`).closest('tr');
        if (!row) return;

        // Obtener datos de las celdas de la fila
        const cells = row.querySelectorAll('td');
        
        // 0: Nombre, 1: Ap. Paterno, 2: Ap. Materno, 3: Correo, 4: Teléfono
        document.getElementById('edit-id').value = userId;
        document.getElementById('edit-nombre').value = cells[0].textContent.trim();
        document.getElementById('edit-apellido_paterno').value = cells[1].textContent.trim();
        document.getElementById('edit-apellido_materno').value = cells[2].textContent.trim();
        document.getElementById('edit-correo').value = cells[3].textContent.trim();
        document.getElementById('edit-telefono').value = cells[4].textContent.trim();

        document.getElementById('editMessage').textContent = ''; // Limpiar mensajes
        document.getElementById('editModal').style.display = 'block';
    }

    // ----------------------------------------------------
    // FUNCIÓN 2: Manejar el envío del formulario por AJAX
    // ----------------------------------------------------
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Detener el envío tradicional del formulario

        const form = e.target;
        const formData = new FormData(form);
        const editMessage = document.getElementById('editMessage');
        const userId = formData.get('id');

        editMessage.textContent = 'Guardando...';
        editMessage.style.color = '#007aff';
        
        // Similuar a tu fetch o XMLHttpRequest en JavaFX/JavaScript
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Verificar si la respuesta es exitosa (código 200-299)
            if (!response.ok) {
                // Lanza un error para ser capturado por .catch
                return response.json().then(err => { throw new Error(err.message || 'Error desconocido.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                editMessage.textContent = data.message;
                editMessage.style.color = 'green';
                
                // Opción A: Actualizar la fila en el HTML (la más rápida)
                updateTableRow(userId, formData);

                // Opción B (Recomendada): Recargar solo la tabla
                // setTimeout(() => { window.location.reload(); }, 1500); 

                setTimeout(() => { document.getElementById('editModal').style.display = 'none'; }, 2000);
            } else {
                editMessage.textContent = data.message;
                editMessage.style.color = 'red';
            }
        })
        .catch(error => {
            editMessage.textContent = 'Error: ' + error.message;
            editMessage.style.color = 'red';
            console.error('AJAX Error:', error);
        });
    });

    // ----------------------------------------------------
    // FUNCIÓN 3: Actualizar la fila sin recargar la página
    // ----------------------------------------------------
    function updateTableRow(userId, formData) {
        const row = document.querySelector(`a[onclick*="${userId}"]`).closest('tr');
        if (row) {
            const cells = row.querySelectorAll('td');
            // Actualizar el texto de las celdas con los nuevos valores del formulario
            cells[0].textContent = formData.get('nombre');
            cells[1].textContent = formData.get('apellido_paterno');
            cells[2].textContent = formData.get('apellido_materno');
            cells[3].textContent = formData.get('correo');
            cells[4].textContent = formData.get('telefono');
        }
    }
</script>
</body>
</html>
