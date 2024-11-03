
<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Variables para filtros
$filtro_producto = $_POST['producto'] ?? '';
$filtro_precio_min = $_POST['precio_min'] ?? '';
$filtro_precio_max = $_POST['precio_max'] ?? '';
$filtro_fecha_inicio = $_POST['fecha_inicio'] ?? '';
$filtro_fecha_fin = $_POST['fecha_fin'] ?? '';

// Construcción de la consulta SQL según los filtros aplicados
$sql = "SELECT id_accesorios_y_componentes, nombre, descripcion, stock, precio, tipo, stockmin, stockmaximo 
        FROM accesorios_y_componentes WHERE 1=1";

if ($filtro_producto) {
    $sql .= " AND nombre LIKE '%$filtro_producto%'";
}
if ($filtro_precio_min && $filtro_precio_max) {
    $sql .= " AND precio BETWEEN $filtro_precio_min AND $filtro_precio_max";
}
if ($filtro_fecha_inicio && $filtro_fecha_fin) {
    $sql .= " AND fecha BETWEEN '$filtro_fecha_inicio' AND '$filtro_fecha_fin'";
}

$result = $conn->query($sql);

?>

<!-- Formulario para aplicar filtros -->
<form method="post" action="">
    <label>Producto:</label>
    <input type="text" name="producto" placeholder="Nombre del producto">
    
    <label>Precio Mínimo:</label>
    <input type="number" name="precio_min" step="0.01" placeholder="0.00">
    
    <label>Precio Máximo:</label>
    <input type="number" name="precio_max" step="0.01" placeholder="0.00">
    
    <label>Fecha Inicio:</label>
    <input type="date" name="fecha_inicio">
    
    <label>Fecha Fin:</label>
    <input type="date" name="fecha_fin">
    
    <button type="submit">Filtrar</button>
</form>

<!-- Tabla de resultados -->
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
                <th>Stock Min</th>
                <th>Stock Máximo</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id_accesorios_y_componentes"] . "</td>
                <td>" . $row["nombre"] . "</td>
                <td>" . $row["descripcion"] . "</td>
                <td>" . $row["stock"] . "</td>
                <td>" . $row["precio"] . "</td>
                <td>" . $row["tipo"] . "</td>
                <td>" . $row["stockmin"] . "</td>
                <td>" . $row["stockmaximo"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados";
}

$conn->close();
?>
