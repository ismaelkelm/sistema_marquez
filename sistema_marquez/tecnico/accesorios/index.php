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
        <a href="../../index.php" class="btn btn-secondary mb-3">Volver</a>

        <h1>Clientes</h1>
        <a href="create.php" class="btn btn-primary mb-3">Agregar Cliente</a>
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
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['teléfono']); ?></td>
                    <td><?php echo htmlspecialchars($row['correo_electrónico']); ?></td>
                    <td><?php echo htmlspecialchars($row['dirección']); ?></td>
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
