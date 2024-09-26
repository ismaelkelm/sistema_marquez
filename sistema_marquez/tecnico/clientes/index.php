<?php
include '../../db.php';

// Consulta para obtener clientes
$sql = "SELECT * FROM clientes";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="../../estilos/bootstrap.css">
    <link rel="stylesheet" href="../../estilos/estilo.css">
</head>
<body>
    <div class="container">
        <h1>Clientes</h1>
        <a href="../../index.php" class="btn btn-secondary mb-3">Volver</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo Electrónico</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['teléfono']); ?></td>
                        <td><?php echo htmlspecialchars($row['correo_electrónico']); ?></td>
                        <td><?php echo htmlspecialchars($row['dirección']); ?></td>
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
