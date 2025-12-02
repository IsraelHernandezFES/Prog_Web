<?php
// ===================================
// 1. CONFIGURACIÓN DE CONEXIÓN
// ===================================

// Asegúrate de que estos valores coincidan con tus credenciales de InfinityFree
$servername = "sql213.infinityfree.com";
$username = "if0_40297195";
$password = "chuchoana2005";
$dbname = "if0_40297195_Usuario";

// ===================================
// 2. LÓGICA DE ELIMINACIÓN
// ===================================

// 2.1. Verificar si se recibió un ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    // Obtener y limpiar el ID del usuario de la URL
    $id_usuario = (int)$_GET['id'];
    
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Usaremos Sentencias Preparadas (Prepared Statements) para mayor SEGURIDAD, 
    // previniendo ataques de inyección SQL (SQL Injection). ¡Buena práctica Full Stack!
    
    // 2.2. Preparar la consulta DELETE
    $sql_delete = "DELETE FROM usuarios WHERE id = ?";
    
    // Preparar y enlazar
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id_usuario); // "i" indica que el parámetro es un entero (integer)

    // 2.3. Ejecutar la consulta
    if ($stmt->execute()) {
        // La eliminación fue exitosa
        // NOTA: Se podría añadir un mensaje de éxito, pero por simplicidad solo redirigimos.
    } else {
        // Manejar el error de ejecución
        echo "Error al intentar eliminar el registro: " . $stmt->error;
        // Podrías añadir un die() si el error es grave
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();

} 
// En caso de que se acceda a eliminar.php sin un ID válido, redirigimos
// o mostramos un error más limpio.
// else {
//     echo "ID de usuario no especificado.";
// }

// 2.4. Redirigir siempre de vuelta al listado principal (index.php)
// Esto asegura que el usuario vea la tabla actualizada.
header("Location: index.php");
exit(); // Es crucial usar exit() después de header() para detener la ejecución del script
?>