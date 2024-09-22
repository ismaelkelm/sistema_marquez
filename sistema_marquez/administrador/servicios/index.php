<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar servicios
$query = "SELECT * FROM servicios";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrador.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Servicios</h1>
    <a href="create.php" class="btn btn-primary mb-3">Agregar Servicio</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Tipo de Servicio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_servicios']); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                <td><?php echo htmlspecialchars(number_format($row['precio_servicio'], 2)); ?></td>
                <td><?php echo htmlspecialchars($row['tipo_servicio']); ?></td>
                <td>
                    <a href="edit.php?id_servicios=<?php echo htmlspecialchars($row['id_servicios']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete.php?id_servicios=<?php echo htmlspecialchars($row['id_servicios']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
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
