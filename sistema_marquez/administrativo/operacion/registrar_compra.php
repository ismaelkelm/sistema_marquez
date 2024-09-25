<?php
include '../../base_datos/db.php'; // Incluye el archivo de conexión

// Consulta para obtener el último registro de comprobante_proveedores
$query = "SELECT * FROM comprobante_proveedores ORDER BY id_comprobante_proveedores DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$ultimo_registro = mysqli_fetch_assoc($result);

// Consulta para obtener todos los proveedores
$query_proveedores = "SELECT id_proveedores, nombre FROM proveedores"; 
$result_proveedores = mysqli_query($conn, $query_proveedores);

// Consulta para obtener todos los accesorios y componentes
$query_accesorios = "SELECT id_accesorios_y_componentes, nombre FROM accesorios_y_componentes"; 
$result_accesorios = mysqli_query($conn, $query_accesorios);

// Manejar la solicitud de registro de compra
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar'])) {
    // Registrar la compra
    $id_proveedor = $_POST['id_proveedor'];
    $id_accesorio = $_POST['id_accesorio'];
    $fecha_compra = $_POST['fecha_compra'];
    $cantidad = $_POST['cantidad'];

    // Generar el número de comprobante
    $ultimo_numero_comprobante = isset($ultimo_registro['num_de_comprobante']) ? intval(substr($ultimo_registro['num_de_comprobante'], 4)) : 0;
    $numero_comprobante_formateado = 'COMP' . str_pad($ultimo_numero_comprobante + 1, 3, '0', STR_PAD_LEFT);

    // Consulta de inserción en la tabla de comprobante_proveedores
    $query = "INSERT INTO comprobante_proveedores (fecha_de_compra, cantidad_comprada, num_de_comprobante, id_accesorios_y_componentes, id_proveedores) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'siisi', $fecha_compra, $cantidad, $numero_comprobante_formateado, $id_accesorio, $id_proveedor);

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
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['preparar'])) {
    // Almacenar valores para mostrar antes de registrar
    $id_proveedor = $_POST['id_proveedor'];
    $id_accesorio = $_POST['id_accesorio'];
    $fecha_compra = $_POST['fecha_compra'];
    $cantidad = $_POST['cantidad'];

    // Obtener nombre del proveedor
    $query_proveedor = "SELECT nombre FROM proveedores WHERE id_proveedores = ?";
    $stmt_proveedor = mysqli_prepare($conn, $query_proveedor);
    mysqli_stmt_bind_param($stmt_proveedor, 'i', $id_proveedor);
    mysqli_stmt_execute($stmt_proveedor);
    $result_proveedor = mysqli_stmt_get_result($stmt_proveedor);
    $proveedor = mysqli_fetch_assoc($result_proveedor);

    // Obtener nombre del accesorio
    $query_accesorio = "SELECT nombre FROM accesorios_y_componentes WHERE id_accesorios_y_componentes = ?";
    $stmt_accesorio = mysqli_prepare($conn, $query_accesorio);
    mysqli_stmt_bind_param($stmt_accesorio, 'i', $id_accesorio);
    mysqli_stmt_execute($stmt_accesorio);
    $result_accesorio = mysqli_stmt_get_result($stmt_accesorio);
    $accesorio = mysqli_fetch_assoc($result_accesorio);
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <h1>Registrar Compra a proveedor</h1>

    <!-- Formulario para registrar la compra -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="id_proveedor">Seleccionar Proveedor</label>
            <select name="id_proveedor" id="id_proveedor" class="form-control" required>
                <option value="">Seleccione un proveedor</option>
                <?php while ($row_proveedor = mysqli_fetch_assoc($result_proveedores)): ?>
                    <option value="<?= $row_proveedor['id_proveedores'] ?>" <?= isset($id_proveedor) && $id_proveedor == $row_proveedor['id_proveedores'] ? 'selected' : '' ?>><?= $row_proveedor['nombre'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_accesorio">Seleccionar Accesorio/Componente</label>
            <select name="id_accesorio" id="id_accesorio" class="form-control" required>
                <option value="">Seleccione un accesorio</option>
                <?php while ($row_accesorio = mysqli_fetch_assoc($result_accesorios)): ?>
                    <option value="<?= $row_accesorio['id_accesorios_y_componentes'] ?>" <?= isset($id_accesorio) && $id_accesorio == $row_accesorio['id_accesorios_y_componentes'] ? 'selected' : '' ?>><?= $row_accesorio['nombre'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fecha_compra">Fecha de Compra</label>
            <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" value="<?= isset($fecha_compra) ? $fecha_compra : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad Comprada</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" value="<?= isset($cantidad) ? $cantidad : '' ?>" required>
        </div>

        <button type="submit" name="preparar" class="btn btn-secondary">Preparar Registro</button>
    </form>

    <?php if (isset($id_proveedor) && isset($id_accesorio)): ?>
        <h3>Confirmar Registro</h3>
        <p><strong>Proveedor:</strong> <?= $proveedor['nombre'] ?></p>
        <p><strong>Accesorio/Componente:</strong> <?= $accesorio['nombre'] ?></p>
        <p><strong>Fecha de Compra:</strong> <?= $fecha_compra ?></p>
        <p><strong>Cantidad Comprada:</strong> <?= $cantidad ?></p>

        <form method="POST" action="">
            <input type="hidden" name="id_proveedor" value="<?= $id_proveedor ?>">
            <input type="hidden" name="id_accesorio" value="<?= $id_accesorio ?>">
            <input type="hidden" name="fecha_compra" value="<?= $fecha_compra ?>">
            <input type="hidden" name="cantidad" value="<?= $cantidad ?>">
            <button type="submit" name="confirmar" class="btn btn-primary">Confirmar Registro</button>
        </form>
    <?php endif; ?>

    <!-- Mostrar el último registro si lo deseas -->
    <?php if (isset($ultimo_registro)): ?>
        <div>
            <h3>Último Registro</h3>
            <p>ID: <?= $ultimo_registro['id_comprobante_proveedores'] ?></p>
            <p>Fecha de Compra: <?= $ultimo_registro['fecha_de_compra'] ?></p>
            <p>Cantidad Comprada: <?= $ultimo_registro['cantidad_comprada'] ?></p>
            <p>Número de Comprobante: <?= $ultimo_registro['num_de_comprobante'] ?></p>
        </div>
    <?php endif; ?>
</div>

<?php include('../../includes/footer.php'); ?>
