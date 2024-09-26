<?php
require_once '../../mi_sistema/base_datos/db.php'; // Asegúrate de que la conexión se establece aquí

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);

    // Ejemplo de datos para inser
    $usuarios = [
        ['nombre' => 'Juan Perez', 'DNI' => '12345678', 'contraseña' => 'admin123', 'email' => 'juan.perez@example.com', 'id_roles' => 1],
        ['nombre' => 'Maria Gomez', 'DNI' => '23456789', 'contraseña' => 'supervisor456', 'email' => 'maria.gomez@example.com', 'id_roles' => 2],
        ['nombre' => 'Carlos Ruiz', 'DNI' => '34567890', 'contraseña' => 'empleado789', 'email' => 'carlos.ruiz@example.com', 'id_roles' => 3],
        ['nombre' => 'Ana Lopez', 'DNI' => '45678901', 'contraseña' => 'tecnico101', 'email' => 'ana.lopez@example.com', 'id_roles' => 4],
        ['nombre' => 'Pedro Martinez', 'DNI' => '56789012', 'contraseña' => 'cliente202', 'email' => 'pedro.martinez@example.com', 'id_roles' => 5]
    ];

    foreach ($usuarios as $usuario) {
        // Encriptar la contraseña
        $hashed_password = password_hash($usuario['contraseña'], PASSWORD_DEFAULT);

        // Preparar la consulta de inserción
        $sql_insert = "INSERT INTO usuarios (nombre, DNI, contraseña, email, id_roles) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt_insert = $conn->prepare($sql_insert)) {
            $stmt_insert->bind_param("ssssi", $usuario['nombre'], $usuario['DNI'], $hashed_password, $usuario['email'], $usuario['id_roles']);
            if ($stmt_insert->execute()) {
                echo "Usuario insertado correctamente: " . $usuario['nombre'] . "<br>";
            } else {
                echo "Error al insertar usuario " . $usuario['nombre'] . ": " . $stmt_insert->error . "<br>";
            }
            $stmt_insert->close();
        } else {
            echo "Error al preparar la consulta de inserción: " . $conn->error;
        }
    }

    $conn->close();
}
?>
