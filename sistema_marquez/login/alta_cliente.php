<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1 style="text-align: center;">Registro de Usuario</h1>

    <div class="container">
        <!-- El formulario debe tener el atributo action apuntando a register_process.php -->
        <form action="register_process.php" method="POST">
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
