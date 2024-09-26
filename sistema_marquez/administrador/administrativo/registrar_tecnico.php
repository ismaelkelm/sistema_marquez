<?php
require_once '../base_datos/db.php';

// Información del nuevo técnico
$nombre = "Juan Pérez";
$contraseña = password_hash("password123", PASSWORD_BCRYPT);
$correo = "juan.perez@example.com";
$dni = "45678901";
$id_rol_tecnico = 3;  // ID del rol 'tecnico'

// Comenzamos la transacción
$conn->begin_transaction();

try {
    // Insertar el nuevo usuario en la tabla 'usuarios'
    $sql_usuario = "INSERT INTO usuarios (nombre, contraseña, id_roles, correo_electronico, dni) VALUES (?, ?, ?, ?, ?)";
    $stmt_usuario = $conn->prepare($sql_usuario);
    $stmt_usuario->bind_param("ssiss", $nombre, $contraseña, $id_rol_tecnico, $correo, $dni);
    $stmt_usuario->execute();

    // Obtener el ID del usuario recién insertado
    $id_usuario = $stmt_usuario->insert_id;

    // Insertar el nuevo técnico en la tabla 'tecnicos'
    $sql_tecnico = "INSERT INTO tecnicos (nombre, especialidad, id_usuario) VALUES (?, ?, ?)";
    $especialidad = "Reparación de Smartphones"; // Ajustar según la especialidad del técnico
    $stmt_tecnico = $conn->prepare($sql_tecnico);
    $stmt_tecnico->bind_param("ssi", $nombre, $especialidad, $id_usuario);
    $stmt_tecnico->execute();

    // Confirmar la transacción
    $conn->commit();

    echo "El técnico ha sido registrado exitosamente.";
} catch (Exception $e) {
    // Si hay un error, revertimos la transacción
    $conn->rollback();
    echo "Error al registrar el técnico: " . $e->getMessage();
}

$conn->close();
?>
