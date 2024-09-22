<?php
// Iniciar sesión si no se ha iniciado ya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
require_once '../base_datos/db.php'; // Asegúrate de que este archivo defina y exporte $conn

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Nombre del usuario técnico que quieres insertar
$nombre = 'nombre_del_usuario_tecnico'; // Cambia esto al nombre real del usuario técnico

// Llamar al procedimiento almacenado para insertar el nuevo técnico
$call_proc = $conn->prepare("CALL InsertarTecnico(?)");
if ($call_proc === false) {
    die("Error en la preparación del procedimiento: " . htmlspecialchars($conn->error));
}
$call_proc->bind_param("s", $nombre);
$call_proc->execute();

if ($call_proc->affected_rows > 0) {
    echo "Técnico insertado correctamente.";
} else {
    echo "Error al insertar el técnico: " . $conn->error;
}

$call_proc->close(); // Cierra el procedimiento almacenado
$conn->close(); // Cierra la conexión a la base de datos
?>
