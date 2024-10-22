<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Fondo de página con gradiente */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .login-container h2 {
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        .login-container .form-label {
            font-weight: 500;
        }
        .login-container .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px;
            font-size: 1rem;
        }
        .login-container .btn-primary:hover {
            background-color: #0056b3;
        }
        .login-container .alert {
            margin-top: 1rem;
        }
        .login-container .text-center p {
            margin-top: 1rem;
            color: #555;
        }
        .login-container .text-center a {
            color: #007bff;
            text-decoration: none;
        }
        .login-container .text-center a:hover {
            text-decoration: underline;
        }
        .btn-secondary {
            background-color: black;
            border: none;
            padding: 10px;
            font-size: 1rem;
            margin-top: 15px;
        }
        .btn-secondary:hover {
            background-color: blue ;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Inicio de Sesión</h2>
        <form action="../login/login_process.php" method="post">
            <div class="form-group mb-3">
                <label for="usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Nombre de Usuario" required>
            </div>

            <div class="form-group mb-3">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="mt-3 text-center">
            <p>¿No tienes una cuenta? <a href="alta_cliente.php">Regístrate aquí</a></p>
            <!-- <p>¿Olvidaste tu nombre de usuario o contraseña? <a href="">Recupera aquí.</a></p> -->
        </div>

        <!-- Botón para volver al índice -->
        <div class="text-center">
            <a href="../index.html" class="btn btn-secondary w-100">Volver al Inicio</a>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
