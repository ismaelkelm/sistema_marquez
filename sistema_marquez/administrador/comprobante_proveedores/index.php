<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Consultar comprobantes de proveedores
$query = "SELECT * FROM comprobante_proveedores";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrador.php" class="btn btn-secondary mb-3">Volver</a>
    <h1 class="mb-4">Comprobantes de Proveedores</h1>
    <a href="create.php" class="btn btn-primary mb-3">Agregar Comprobante</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Compra</th>
                <th>Cantidad Comprada</th>
                <th>Número de Comprobante</th>
                <th>ID Accesorios/Componentes</th>
                <th>ID Proveedor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_comprobante_proveedores']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_de_compra']); ?></td>
                <td><?php echo htmlspecialchars($row['cantidad_comprada']); ?></td>
                <td><?php echo htmlspecialchars($row['num_de_comprobante']); ?></td>
                <td><?php echo htmlspecialchars($row['id_accesorios_y_componentes']); ?></td>
                <td><?php echo htmlspecialchars($row['id_proveedores']); ?></td>
                <td>
                    <a href="edit.php?id=<?php echo htmlspecialchars($row['id_comprobante_proveedores']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete.php?id=<?php echo htmlspecialchars($row['id_comprobante_proveedores']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
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
