<?php
include '../../base_datos/db.php';

$id = $_GET['id_usuario'];

$query = "SELECT * FROM usuario WHERE id_usuario = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'] ? password_hash($_POST['contraseña'], PASSWORD_DEFAULT) : $row['contraseña']; // Encriptar nueva contraseña si se proporciona
    $correo_electronico = $_POST['correo_electronico'];
    $dni = $_POST['dni'];
    $id_roles = $_POST['id_roles'];

    $query = "UPDATE usuario SET nombre = ?, contraseña = ?, correo_electronico = ?, dni = ?, id_roles = ? WHERE id_usuario = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssii', $nombre, $contraseña, $correo_electronico, $dni, $id_roles, $id);

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
    <h1>Editar Usuario</h1>
    <form method="POST">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($row['nombre']); ?>" required>
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="contraseña" class="form-control">
            <small>Deja vacío si no deseas cambiar la contraseña.</small>
        </div>
        <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" name="correo_electronico" class="form-control" value="<?php echo htmlspecialchars($row['correo_electronico']); ?>" required>
        </div>
        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni" class="form-control" value="<?php echo htmlspecialchars($row['dni']); ?>" required>
        </div>
        <div class="form-group">
            <label>ID Rol</label>
            <input type="number" name="id_roles" class="form-control" value="<?php echo htmlspecialchars($row['id_roles']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Actualizar</button>
        <a href="index.php" class="btn btn-secondary mt-3">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
