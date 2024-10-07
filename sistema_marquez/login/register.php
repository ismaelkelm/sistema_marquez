<?php
session_start();
require_once '../base_datos/db.php';

// Consultar los roles habilitados desde la base de datos
$sql_roles = "SELECT id_roles, nombre FROM roles WHERE habilitado = 1";
$roles = [];
if ($stmt_roles = $conn->prepare($sql_roles)) {
    $stmt_roles->execute();
    $stmt_roles->bind_result($id_roles, $descripcion);
    while ($stmt_roles->fetch()) {
        $roles[] = ['id_roles' => $id_roles, 'descripcion' => $descripcion];
    }
    $stmt_roles->close();
} else {
    echo "Error al consultar los roles: " . $conn->error;
}

$conn->close();

// Redirigir a login.php si no hay roles habilitados
if (empty($roles)) {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('../presentacion/fondoazul.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .container {
            max-width: 500px;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 2rem;
            border-radius: 12px;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            background-color: #ffffff;
            color: #495057;
        }
        .form-control:focus {
            border-color: #007bff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #ffffff;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .message-container {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            display: <?php echo isset($message) ? 'block' : 'none'; ?>;
        }
        .message-container.success {
            background-color: #28a745;
        }
        .message-container.error {
            background-color: #dc3545;
        }
        .message-container p {
            margin: 0;
        }
        .message-container a {
            color: #007bff;
            font-weight: bold;
        }
        .message-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php if (isset($message)): ?>
        <div class="message-container <?php echo htmlspecialchars($message_type); ?>">
            <p><?php echo htmlspecialchars($message); ?></p>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Registro de Usuario</h2>
        <form action="register_process.php" method="post">
            <div class="form-group">
                <label for="id_roles">Rol</label>
                <select class="form-control" id="id_roles" name="id_roles" required>
                    <option value="" disabled selected>Selecciona un rol</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo htmlspecialchars($role['id_roles']); ?>">
                            <?php echo htmlspecialchars($role['descripcion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre Usuario</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre Usuario" required>
            </div>

            <div class="form-group">
                <label for="dni">DNI Usuario</label>
                <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI Usuario" required>
            </div>

            <div class="form-group">
                <label for="contraseña">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Contraseña" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo Electrónico" required>
            </div>

            <button type="submit" class="btn btn-primary">Registrar</button>
            <a href="../login/login.php" class="btn btn-secondary">Volver atrás</a>
        </form>

        <p class="mt-3">¿Ya tienes una cuenta? <a href="../login/login.php">Inicia sesión aquí.</a></p>
    </div>
</body>
</html>
