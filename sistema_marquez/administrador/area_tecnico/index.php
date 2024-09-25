<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php'; // Ajusta la ruta según la ubicación del archivo

// Verificar si la conexión fue exitosa
if (!$conn) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Consultar áreas técnicas
$query = "SELECT * FROM area_tecnico";
$result = mysqli_query($conn, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="../administrador.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Áreas Técnicas</h1>
    <a href="create.php" class="btn btn-primary mb-3">Agregar Área Técnica</a>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>ID Técnico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_area_tecnico']); ?></td>
                <td><?php echo htmlspecialchars($row['descripcion_area']); ?></td>
                <td><?php echo htmlspecialchars($row['id_tecnicos']); ?></td>
                <td>
                    <a href="edit.php?id=<?php echo htmlspecialchars($row['id_area_tecnico']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete.php?id=<?php echo htmlspecialchars($row['id_area_tecnico']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../../includes/footer.php'); ?>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
