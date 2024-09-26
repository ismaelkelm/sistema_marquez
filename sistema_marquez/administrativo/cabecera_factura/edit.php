<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_clientes = $_POST['id_clientes'];
    $nombre = $_POST['id_usuario'];

    $query = "UPDATE clientes SET id_clientes='$nombre', apellido='$apellido', telefono='$telefono', correo_electronico='$correo_electronico', direccion='$direccion', dni='$dni' WHERE id_clientes=$id_clientes";

    if (mysqli_query($conn, $query)) {
        header('Location: index.php');
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
} else {
    $id_clientes = $_GET['id'];
    $query = "SELECT * FROM cliente_con_usuario WHERE id_clientes=$id_clientes";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Editar Cliente</h1>
    <form method="post" action="">
        <input type="hidden" name="id_clientes" value="<?php echo htmlspecialchars($row['id_clientes']); ?>">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($row['nombre']); ?>" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" class="form-control" value="<?php echo htmlspecialchars($row['apellido']); ?>" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($row['telefono']); ?>" required>
        </div>
        <div class="form-group">
            <label for="correo_electronico">Correo Electrónico:</label>
            <input type="email" id="correo_electronico" name="correo_electronico" class="form-control" value="<?php echo htmlspecialchars($row['correo_electronico']); ?>" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <textarea id="direccion" name="direccion" class="form-control" required><?php echo htmlspecialchars($row['direccion']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" class="form-control" value="<?php echo htmlspecialchars($row['dni']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
