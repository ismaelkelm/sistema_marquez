<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar tipos de pago
$query = "SELECT * FROM tipo_de_pago";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Iniciar sesión si no se ha hecho
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tipo_pago'])) {
    // Guardar el ID del tipo de pago en la sesión
    $_SESSION['id_tipo_pago'] = intval($_POST['tipo_pago']);
    // Redirigir o mostrar un mensaje de éxito (opcional)
    header("Location: success.php"); // Cambia a la página que desees
    exit();
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrativo.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Seleccionar Tipo de Pago</h1>
    
    <!-- Formulario para seleccionar tipo de pago -->
    <form method="post" action="">
        <div class="form-group">
            <label for="tipo_pago">Selecciona un Tipo de Pago</label>
            <select name="tipo_pago" id="tipo_pago" class="form-control" required>
                <option value="">Seleccione</option>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo htmlspecialchars($row['id_tipo_de_pago']); ?>">
                        <?php echo htmlspecialchars($row['descripcion_de_pago']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
