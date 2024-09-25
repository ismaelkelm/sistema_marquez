<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Verificar si el ID está en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de permiso no especificado.');
}

$id = $_GET['id'];

// Consultar el permiso
$query = "SELECT * FROM permisos WHERE id_permisos = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die('Permiso no encontrado.');
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];

    // Actualizar permiso
    $query = "UPDATE permisos SET descripcion = ? WHERE id_permisos = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $descripcion, $id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirigir a la lista de permisos
        exit();
    } else {
        die("Error al actualizar: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Permiso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Editar Permiso</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($row['descripcion']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
</body>
</html>
