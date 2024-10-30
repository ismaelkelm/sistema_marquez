<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

$filtro_producto = $_POST['producto'] ?? '';

$sql = "SELECT * FROM accesorios_y_componentes WHERE nombre LIKE '%$filtro_producto%'";
$result = $conn->query($sql);
?>

<h2>Detalle por Producto</h2>
<form method="post" action="detalle_producto.php">
    <label>Producto:</label>
    <input type="text" name="producto" placeholder="Nombre del producto">
    <button type="submit">Buscar</button>
</form>

<?php
if ($result && $result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Tipo</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id_accesorios_y_componentes"] . "</td>
                <td>" . $row["nombre"] . "</td>
                <td>" . $row["descripcion"] . "</td>
                <td>" . $row["stock"] . "</td>
                <td>" . $row["precio"] . "</td>
                <td>" . $row["tipo"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados";
}

$conn->close();
?>
