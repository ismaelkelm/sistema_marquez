<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar proveedores
$query = "SELECT * FROM proveedores";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Iniciar sesión si no se ha hecho
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['proveedor'])) {
    // Guardar el ID del proveedor en la sesión
    $_SESSION['id_proveedor'] = intval($_POST['proveedor']);
    // Redirigir o mostrar un mensaje de éxito (opcional)
    header("Location: success.php"); // Cambia a la página que desees
    exit();
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrativo.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Proveedores</h1>
    
    <!-- Formulario para seleccionar proveedor -->
    <form method="post" action="">
        <div class="form-group">
            <label for="proveedor">Selecciona un Proveedor</label>
            <select name="proveedor" id="proveedor" class="form-control" required>
                <option value="">Seleccione</option>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo htmlspecialchars($row['id_proveedores']); ?>">
                        <?php echo htmlspecialchars($row['nombre']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <!-- Tabla de proveedores (sin acciones) -->
    <h2 class="mt-5">Lista de Proveedores</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Reseteamos el puntero de resultados para mostrar la tabla nuevamente
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_proveedores']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['contacto']); ?></td>
                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                <td><?php echo htmlspecialchars($row['correo_electronico']); ?></td>
                <td><?php echo htmlspecialchars($row['direccion']); ?></td>
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
