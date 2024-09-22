<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];

    // Insertar nuevo permiso
    $query = "INSERT INTO permisos (descripcion) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $descripcion);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirigir a la lista de permisos
        exit();
    } else {
        die("Error al insertar: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Permiso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Agregar Permiso</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
</div>
</body>
</html>
