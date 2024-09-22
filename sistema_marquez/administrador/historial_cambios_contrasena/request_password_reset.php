<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = mysqli_real_escape_string($conn, $_POST['correo_electronico']);
    
    // Buscar el usuario por correo electrónico
    $query = "SELECT id_usuarios FROM usuarios WHERE correo_electronico = '$correo'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $id_usuario = $user['id_usuarios'];
        
        // Generar un token de recuperación
        $token = bin2hex(random_bytes(32));
        $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Insertar el token en la tabla de historial
        $query = "INSERT INTO historial_cambios_contrasena (id_usuario, token_recuperacion, fecha_expiracion_token) 
                  VALUES ('$id_usuario', '$token', '$fecha_expiracion')";
        mysqli_query($conn, $query);
        
        // Enviar el correo electrónico con el enlace de restablecimiento
        $reset_link = "https://tu_dominio.com/reset_password.php?token=$token";
        $subject = "Restablecimiento de Contraseña";
        $message = "Para restablecer su contraseña, haga clic en el siguiente enlace: $reset_link";
        mail($correo, $subject, $message);
        
        echo "Se ha enviado un correo electrónico con el enlace para restablecer su contraseña.";
    } else {
        echo "No se encontró una cuenta con ese correo electrónico.";
    }
}
?>

<form method="POST" action="request_password_reset.php">
    <label for="correo_electronico">Correo Electrónico:</label>
    <input type="email" name="correo_electronico" id="correo_electronico" required>
    <button type="submit">Enviar Enlace de Restablecimiento</button>
</form>
