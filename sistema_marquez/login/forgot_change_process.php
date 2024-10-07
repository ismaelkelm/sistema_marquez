<?php
session_start();
require_once '../base_datos/db.php'; // Ajusta la ruta según sea necesario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);
    $tipo_cambio = trim($_POST["tipo_cambio"]);

    if (empty($correo) || empty($tipo_cambio)) {
        echo "Por favor, ingrese su correo electrónico y seleccione el tipo de cambio.";
        exit;
    }

    $sql = "SELECT id_usuario, usuario FROM usuario WHERE correo = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $usuario);
            $stmt->fetch();

            // Generar un token único
            $token = bin2hex(random_bytes(50));
            $expires = date("U") + 3600; // El token expirará en 1 hora

            // Almacenar el token en la base de datos
            $sql_insert = "INSERT INTO password_resets (user_id, token, expires, tipo_cambio) VALUES (?, ?, ?, ?)";
            if ($stmt_insert = $conn->prepare($sql_insert)) {
                $stmt_insert->bind_param("issi", $user_id, $token, $expires, $tipo_cambio);
                $stmt_insert->execute();
                $stmt_insert->close();

                // Enviar el correo con el enlace de recuperación
                $reset_link = "http://localhost/koki/sistema%20celular%202024/sistema_marquez/login/reset_change.php?token=" . $token;
                $subject = "Solicitud de cambio de " . ($tipo_cambio == 'usuario' ? "Nombre de Usuario" : "Contraseña");
                $message = "Haz clic en el siguiente enlace para " . ($tipo_cambio == 'usuario' ? "cambiar tu nombre de usuario" : "restablecer tu contraseña") . ": " . $reset_link;
                $headers = "From: no-reply@example.com";

                if (mail($correo, $subject, $message, $headers)) {
                    echo "Se ha enviado un enlace de recuperación a su correo electrónico.";
                } else {
                    echo "Error al enviar el correo.";
                }
            } else {
                echo "Error en la preparación de la consulta de recuperación.";
            }
        } else {
            echo "No se encontró una cuenta con ese correo electrónico.";
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }
    $conn->close();
} else {
    header("Location: forgot_change.html");
    exit;
}
?>
