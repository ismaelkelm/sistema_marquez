<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Consultar todos los movimientos
$query = "SELECT * FROM movimientos";
$result = mysqli_query($conn, $query);
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="create.php" class="btn btn-primary mb-3">Agregar Nuevo Movimiento</a>
    <h1 class="mb-4">Lista de Movimientos</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo de Movimiento</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>ID Recibo</th>
                <th>ID Ticket</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($movimiento = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($movimiento['id_movimiento']); ?></td>
                    <td><?php echo htmlspecialchars($movimiento['tipo_movimiento']); ?></td>
                    <td><?php echo htmlspecialchars($movimiento['monto']); ?></td>
                    <td><?php echo htmlspecialchars($movimiento['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($movimiento['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($movimiento['id_recibo']); ?></td>
                    <td><?php echo htmlspecialchars($movimiento['id_ticket']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo htmlspecialchars($movimiento['id_movimiento']); ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="delete.php?id=<?php echo htmlspecialchars($movimiento['id_movimiento']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este movimiento?');">Eliminar</a>
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
