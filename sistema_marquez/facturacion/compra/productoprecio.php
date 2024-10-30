<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

$filtro_precio_min = $_POST['precio_min'] ?? '';
$filtro_precio_max = $_POST['precio_max'] ?? '';

$sql = "SELECT * FROM accesorios_y_componentes WHERE precio BETWEEN $filtro_precio_min AND $filtro_precio_max";
$result = $conn->query($sql);
?>

<h2>Producto / Precio</h2>
<form method="post" action="producto_precio.php">
    <label>Precio Mínimo:</label>
    <input type="number" name="precio_min" step="0.01" placeholder="0.00">
    
    <label>Precio Máximo:</label>
    <input type="number" name="precio_max" step="0.01" placeholder="0.00">
    
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
