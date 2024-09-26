<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Obtener la lista de proveedores
$proveedores_query = "SELECT id_proveedores, nombre FROM proveedores";
$proveedores_result = mysqli_query($conn, $proveedores_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_proveedor = $_POST['id_proveedor'];
    $monto_total = $_POST['monto_total'];
    $fecha_compra = $_POST['fecha_compra'];
    $descripcion = $_POST['descripcion'];

    // Consulta de inserción en la tabla de compras
    $query = "INSERT INTO compras (id_proveedor, monto_total, fecha_compra, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sdss', $id_proveedor, $monto_total, $fecha_compra, $descripcion);

    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Mostrar mensaje de éxito y redirigir después de 2 segundos
        echo "<script>
                alert('Compra registrada con éxito');
                setTimeout(function() {
                    window.location.href = 'http://sistema.local.com/administrativo/compras/registrar_compra.php';
                }, 2000);
              </script>";
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <h1>Registrar Compra</h1>
    <form method="POST">
        <div class="form-group">
            <label>Proveedor</label>
            <select name="id_proveedor" class="form-control" required>
                <option value="">Seleccione un proveedor</option>
                <?php while ($row = mysqli_fetch_assoc($proveedores_result)): ?>
                    <option value="<?php echo $row['id_proveedores']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Monto Total</label>
            <input type="number" name="monto_total" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label>Fecha de Compra</label>
            <input type="date" name="fecha_compra" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="http://sistema.local.com/administrativo/compras/" class="btn btn-secondary">Volver</a>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>
