<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

$filtro_fecha_inicio = $_POST['fecha_inicio'] ?? '';
$filtro_fecha_fin = $_POST['fecha_fin'] ?? '';

$sql = "SELECT * FROM accesorios_y_componentes WHERE fecha BETWEEN '$filtro_fecha_inicio' AND '$filtro_fecha_fin'";
$result = $conn->query($sql);
?>

<h2>Detalle por Fecha</h2>
<form method="post" action="detalle_fecha.php">
    <label>Fecha Inicio:</label>
    <input type="date" name="fecha_inicio">
    
    <label>Fecha Fin:</label>
    <input type="date" name="fecha_fin">
    
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
