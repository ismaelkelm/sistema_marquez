<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar accesorios
$query = "SELECT * FROM accesorios_y_componentes";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Iniciar sesión si no se ha hecho
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accesorio'])) {
    // Guardar el ID del accesorio en la sesión
    $_SESSION['id_accesorio'] = intval($_POST['accesorio']);
    // Redirigir o mostrar un mensaje de éxito (opcional)
    header("Location: success.php"); // Cambia a la página que desees
    exit();
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrativo.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Accesorios y Componentes</h1>
    
    <!-- Formulario para seleccionar accesorio -->
    <form method="post" action="">
        <div class="form-group">
            <label for="accesorio">Selecciona un Accesorio</label>
            <select name="accesorio" id="accesorio" class="form-control" required>
                <option value="">Seleccione</option>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo htmlspecialchars($row['id_accesorios_y_componentes']); ?>">
                        <?php echo htmlspecialchars($row['nombre']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <!-- Tabla de accesorios (sin acciones) -->
    <h2 class="mt-5">Lista de Accesorios y Componentes</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Reseteamos el puntero de resultados para mostrar la tabla nuevamente
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_accesorios_y_componentes']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                <td><?php echo htmlspecialchars(number_format($row['precio'], 2)); ?></td>
                <td><?php echo htmlspecialchars($row['stock']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
