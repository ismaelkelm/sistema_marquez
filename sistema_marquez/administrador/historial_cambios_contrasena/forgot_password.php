// forgot_password.php

<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo_electronico = mysqli_real_escape_string($conn, $_POST['correo_electronico']);

    // Verificar si el correo electrónico está registrado
    $query = "SELECT id_usuarios FROM usuarios WHERE correo_electronico = '$correo_electronico'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $id_usuario = $row['id_usuarios'];

        // Generar un token único para la recuperación de contraseña
        $token = bin2hex(random_bytes(50)); // Genera un token seguro

        // Calcular la fecha de expiración (1 hora desde ahora)
        $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insertar el token y la fecha de expiración en la base de datos
        $query = "INSERT INTO historial_cambios_contrasena (id_usuario, token_recuperacion, fecha_expiracion_token) 
                  VALUES ('$id_usuario', '$token', '$fecha_expiracion')";
        mysqli_query($conn, $query);

        // Enviar el token al correo del usuario (esto es solo un ejemplo, necesitarías configurar el envío de correo)
        $url_recuperacion = "http://example.com/reset_password.php?token=$token";
        mail($correo_electronico, "Restablecimiento de Contraseña", "Haz clic en el siguiente enlace para restablecer tu contraseña: $url_recuperacion");

        echo "Se ha enviado un enlace de restablecimiento de contraseña a tu correo electrónico.";
    } else {
        echo "No se encontró una cuenta con ese correo electrónico.";
    }
}
?>

<!-- Formulario para solicitar restablecimiento de contraseña -->
<form method="POST" action="forgot_password.php">
    <div class="form-group">
        <label for="correo_electronico">Correo Electrónico:</label>
        <input type="email" name="correo_electronico" id="correo_electronico" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Solicitar Restablecimiento</button>
</form>
