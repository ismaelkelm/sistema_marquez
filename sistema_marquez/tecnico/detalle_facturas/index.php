<?php
include '../../base_datos/db.php';

$query = "SELECT * FROM detalle_facturas";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Facturas</title>
    <link rel="stylesheet" href="../../estilos/bootstrap.css">
    <link rel="stylesheet" href="../../estilos/estilo.css">
</head>
<body>
    <div class="container">
        <a href="../../index.php" class="btn btn-secondary mb-3">Volver</a>
        <h1>Detalle de Facturas</h1>
        <a href="create.php" class="btn btn-primary mb-3">Agregar Detalle</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Factura ID</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['factura_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($row['precio_unitario']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning">Editar</a>
                        <a href="delete.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
