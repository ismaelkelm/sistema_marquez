<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php';

// Verificar si se ha enviado el ID para editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos actuales del área técnica
    $query = "SELECT * FROM area_tecnico WHERE id_area_tecnico = $id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $descripcion = $row['descripcion_area'];
        $id_tecnico = $row['id_tecnicos'];
    }
}

// Verificar si se ha enviado el formulario para actualizar los datos
if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $descripcion = $_POST['descripcion'];
    $id_tecnico = $_POST['id_tecnicos'];

    // Actualizar los datos en la base de datos
    $query = "UPDATE area_tecnico SET descripcion_area = '$descripcion', id_tecnicos = '$id_tecnico' WHERE id_area_tecnico = $id";
    mysqli_query($conn, $query);

    // Redirigir a la página principal
    header("Location: index.php");
}

?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Editar Área Técnica</h1>
    
    <form action="edit.php?id=<?php echo $_GET['id']; ?>" method="POST">
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="<?php echo htmlspecialchars($descripcion); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Técnico</label>
            <input type="number" name="id_tecnicos" class="form-control" value="<?php echo htmlspecialchars($id_tecnico); ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary mt-3">Actualizar</button>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
