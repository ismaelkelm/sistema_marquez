<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar clientes
$query = "SELECT * FROM clientes";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Iniciar sesión si no se ha hecho
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cliente'])) {
    // Guardar el ID del cliente en la sesión
    $_SESSION['id_cliente'] = intval($_POST['cliente']);
    // Redirigir o mostrar un mensaje de éxito (opcional)
    header("Location: success.php"); // Cambia a la página que desees
    exit();
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrativo.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Clientes</h1>
    
    <!-- Formulario para seleccionar cliente -->
    <form method="post" action="">
        <div class="form-group">
            <label for="cliente">Selecciona un Cliente</label>
            <select name="cliente" id="cliente" class="form-control" required>
                <option value="">Seleccione</option>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo htmlspecialchars($row['id_clientes']); ?>">
                        <?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <!-- Tabla de clientes (sin acciones) -->
    <h2 class="mt-5">Lista de Clientes</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Dirección</th>
                <th>DNI</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Reseteamos el puntero de resultados para mostrar la tabla nuevamente
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_clientes']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                <td><?php echo htmlspecialchars($row['correo_electronico']); ?></td>
                <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                <td><?php echo htmlspecialchars($row['dni']); ?></td>
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
