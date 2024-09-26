<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar facturas
$query = "SELECT * FROM cabecera_factura";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrativo.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Cabecera de Facturas</h1>
    <a href="create.php" class="btn btn-primary mb-3">Agregar Factura</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Subtotal</th>
                <th>Impuestos</th>
                <th>Total</th>
                <th>ID Cliente</th>
                <th>ID Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_cabecera_factura']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_factura']); ?></td>
                <td><?php echo htmlspecialchars($row['subtotal_factura']); ?></td>
                <td><?php echo htmlspecialchars($row['impuestos']); ?></td>
                <td><?php echo htmlspecialchars($row['total_factura']); ?></td>
                <td><?php echo htmlspecialchars($row['id_clientes']); ?></td>
                <td><?php echo htmlspecialchars($row['id_usuario']); ?></td>
                <td>
                    <a href="edit.php?id=<?php echo htmlspecialchars($row['id_cabecera_factura']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete.php?id=<?php echo htmlspecialchars($row['id_cabecera_factura']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
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
