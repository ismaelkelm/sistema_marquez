<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar notificaciones
$query = "SELECT * FROM notificaciones";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrador.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Notificaciones</h1>
    <a href="create.php" class="btn btn-primary mb-3">Agregar Notificación</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mensaje</th>
                <th>Fecha de Envío</th>
                <th>Estado</th>
                <th>Número de Orden</th>
                <th>ID Pedido de Reparación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_notificaciones']); ?></td>
                <td><?php echo htmlspecialchars($row['mensaje']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_de_envío']); ?></td>
                <td><?php echo htmlspecialchars($row['estado']); ?></td>
                <td><?php echo htmlspecialchars($row['numero_orden']); ?></td>
                <td><?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?></td>
                <td>
                    <a href="edit.php?id=<?php echo htmlspecialchars($row['id_notificaciones']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete.php?id=<?php echo htmlspecialchars($row['id_notificaciones']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../../includes/footer.php'); ?>
<?php mysqli_close($conn); ?>
