<?php
// ¡IMPORTANTE! Requerimos la sesión aquí si no está en gestor.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Si no está logeado, devolver un error JSON en lugar de redirigir
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
    exit;
}

// 1. CONFIGURACIÓN DE CONEXIÓN - Mantener
$servername = "sql213.infinityfree.com";
$username = "if0_40297195";
$password = "chuchoana2005";
$dbname = "if0_40297195_Usuario";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error de conexión con la DB.']);
    exit;
}

// 2. LÓGICA DE PROCESAMIENTO (SOLO POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Configurar encabezado para respuesta JSON
    header('Content-Type: application/json');

    // 2.1. Recoger y limpiar datos
    $id = (int)($_POST['id'] ?? 0);
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
    $apPaterno = $conn->real_escape_string($_POST['apellido_paterno'] ?? '');
    $apMaterno = $conn->real_escape_string($_POST['apellido_materno'] ?? '');
    $correo = $conn->real_escape_string($_POST['correo'] ?? '');
    $telefono = $conn->real_escape_string($_POST['telefono'] ?? '');
    
    // 2.2. Consulta de ACTUALIZACIÓN (UPDATE)
    // Usando Sentencias Preparadas
    $sql_update = "UPDATE usuarios SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, correo = ?, telefono = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    
    // Validar preparación
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error de preparación SQL: ' . $conn->error]);
        $conn->close();
        exit;
    }

    $stmt->bind_param("sssssi", $nombre, $apPaterno, $apMaterno, $correo, $telefono, $id);

    if ($stmt->execute()) {
        // Éxito: devolver JSON
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado con éxito.']);
    } else {
        // Error: devolver JSON con error
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario: ' . $stmt->error]);
    }
    
    $stmt->close();

} else if (isset($_GET['id'])) {
    // Si se accede con GET, es para cargar los datos en el modal.
    // Dejaremos esta lógica en gestor.php o crearemos un endpoint GET separado.
    // POR AHORA, para simplificar, eliminamos la lógica GET de editar.php
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);

} else {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
}

$conn->close();
exit; // Aseguramos que nada más se ejecute
?>