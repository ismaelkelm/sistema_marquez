<?php
include '../../base_datos/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $tipo = $_POST['tipo'];
    $stockmin = $_POST['stockmin'];
    $stockmax = $_POST['stockmax'];
    // Preparar la consulta de inserción con todos los campos
    $query = "INSERT INTO accesorios_y_componentes (nombre, descripcion, precio, stock, tipo, stockmin, stockmaximo
) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdiiii", $nombre, $descripcion, $precio, $stock, $tipo, $stockmin, $stockmax);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        die("Error en la inserción: " . $conn->error);
    }
}
?>

<?php include('../../includes/header.php'); ?>

<div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>

    <h1 class="mb-4">Agregar Accesorio</h1>
    <form action="create.php" method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <input type="text" class="form-control" id="tipo" name="tipo" required>
        </div>
        <div class="mb-3">
            <label for="stockmin" class="form-label">Stock Mínimo</label>
            <input type="number" class="form-control" id="stockmin" name="stockmin" required>
        </div>
        <div class="mb-3">
            <label for="stockmax" class="form-label">Stock Maximo</label>
            <input type="number" class="form-control" id="stockmax" name="stockmax" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
</div>

<?php include('../../includes/footer.php'); ?>

<?php
mysqli_close($conn);
?>
