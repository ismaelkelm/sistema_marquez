<?php
include '../../base_datos/db.php'; // Conexión a la base de datos

// Consulta para mostrar resumen de stock y alertas de stock bajo
$sql = "SELECT id_accesorios_y_componentes, nombre, stock, stockmin, stockmaximo FROM accesorios_y_componentes";
$result = $conn->query($sql);
?>

<h2>Resumen de Stock</h2>

<?php
if ($result && $result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Stock Mínimo</th>
                <th>Stock Máximo</th>
                <th>Estado</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        $estado = ($row["stock"] < $row["stockmin"]) ? "Stock Bajo" : "Suficiente";
        echo "<tr>
                <td>" . $row["id_accesorios_y_componentes"] . "</td>
                <td>" . $row["nombre"] . "</td>
                <td>" . $row["stock"] . "</td>
                <td>" . $row["stockmin"] . "</td>
                <td>" . $row["stockmaximo"] . "</td>
                <td>" . $estado . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron resultados";
}

$conn->close();
?>
