// reset_password.php

<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $nueva_contrasena = mysqli_real_escape_string($conn, $_POST['nueva_contrasena']);

    // Verificar el token y su estado
    $query = "SELECT id_usuario, estado_token, fecha_expiracion_token FROM historial_cambios_contrasena WHERE token_recuperacion = '$token'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $id_usuario = $row['id_usuario'];
        $estado_token = $row['estado_token'];
        $fecha_expiracion_token = $row['fecha_expiracion_token'];

        // Verificar si el token es válido
        if ($estado_token == 'activo' && strtotime($fecha_expiracion_token) > time()) {
            // Actualizar la contraseña del usuario
            $hash_contrasena = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $query = "UPDATE usuarios SET contraseña = '$hash_contrasena' WHERE id_usuarios = $id_usuario";
            mysqli_query($conn, $query);

            // Marcar el token como utilizado
            $query = "UPDATE historial_cambios_contrasena SET estado_token = 'utilizado' WHERE token_recuperacion = '$token'";
            mysqli_query($conn, $query);

            echo "Tu contraseña ha sido actualizada correctamente.";
        } else {
            echo "El token es inválido o ha expirado.";
        }
    } else {
        echo "El token de recuperación no es válido.";
    }
}
?>

<!-- Formulario para restablecer la contraseña -->
<form method="POST" action="reset_password.php">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
    <div class="form-group">
        <label for="nueva_contrasena">Nueva Contraseña:</label>
        <input type="password" name="nueva_contrasena" id="nueva_contrasena" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
</form>
