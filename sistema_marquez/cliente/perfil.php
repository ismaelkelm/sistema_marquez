<?php
// Iniciar sesión si no se ha iniciado ya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../base_datos/db.php'; // Usar require_once para evitar inclusiones múltiples

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Obtener el ID del usuario de la sesión
$user_id = $_SESSION['user_id'];

// Consultar los datos del usuario en la base de datos
$query = "SELECT nombre, correo_electronico, dni FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the parameter
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el usuario
if ($result->num_rows > 0) {
    // Obtener los datos del usuario
    $usuario = $result->fetch_assoc();
} else {
    echo "Usuario no encontrado.";
    exit;
}

// Cerrar la declaración
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <h2>Perfil de Usuario</h2>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($usuario['correo_electronico']); ?></p>
        <p><strong>DNI:</strong> <?php echo htmlspecialchars($usuario['dni']); ?></p>
        
        <div class="mt-4">
            <a href="cliente.php" class="btn btn-secondary">Volver</a> 
        </div>
    </div>
</body>
</html>
