<?php
include '../../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Encriptar contraseña
    $correo_electronico = $_POST['correo_electronico'];
    $dni = $_POST['dni'];
    $id_roles = $_POST['id_roles'];

    $query = "INSERT INTO usuario (nombre, contraseña, correo_electronico, dni, id_roles) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssi', $nombre, $contraseña, $correo_electronico, $dni, $id_roles);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <h1>Agregar Usuario</h1>
    <form method="POST">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="contraseña" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="correo_electronico" class="form-control" required>
        </div>
        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni" class="form-control" required>
        </div>
        <div class="form-group">
            <label>ID Rol</label>
            <input type="number" name="id_roles" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
