<?php
include '../../db.php';

// Consulta para obtener detalles de reparaciones
$sql = "SELECT * FROM detalle_reparaciones";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Reparaciones</title>
    <link rel="stylesheet" href="../../estilos/bootstrap.css">
    <link rel="stylesheet" href="../../estilos/estilo.css">
</head>
<body>
    <div class="container">
        <h1>Detalle de Reparaciones</h1>
        <a href="../../index.php" class="btn btn-secondary mb-3">Volver</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reparación ID</th>
                    <th>Pieza ID</th>
                    <th>Cantidad Usada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['reparacion_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['pieza_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad_usada']); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning">Editar</a>
                            <a href="eliminar.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
