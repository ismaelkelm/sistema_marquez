<?php
include '../../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descripcion = $_POST['descripcion']; // Cambiado de 'nombre' a 'descripcion'

    // Usar consultas preparadas para evitar inyecciones SQL
    $query = "INSERT INTO area_tecnico (descripcion_area) VALUES (?)"; // Ajustado a 'descripcion_area'
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $descripcion);

    // Verificar si la inserción fue exitosa
    if ($stmt->execute()) {
        header('Location: index.php'); // Redirigir después de agregar el área técnica
        exit();
    } else {
        die("Error en la inserción: " . $conn->error);
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Agregar Área Técnica</h1>
    <form action="create.php" method="post">
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label> <!-- Cambiado 'nombre' a 'descripcion' -->
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
