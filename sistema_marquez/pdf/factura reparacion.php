<?php
// Asumiendo que la conexión a la base de datos ya está establecida

// Reemplaza {ID_PEDIDO} con el id del pedido que quieras facturar
$id_pedido = 1;

$query_factura = "
    SELECT 
        c.nombre,
        c.apellido,
        c.cuit,
        c.direccion,
        pr.numero_orden,
        dr.descripcion AS descripcion_reparacion,
        s.descripcion AS servicio_descripcion,
        s.precio_servicio
    FROM clientes c
    JOIN pedidos_de_reparacion pr ON c.id_clientes = pr.id_clientes
    JOIN detalle_reparaciones dr ON pr.id_pedidos_de_reparacion = dr.id_pedidos_de_reparacion
    JOIN servicios s ON dr.id_servicios = s.id_servicios
    WHERE pr.id_pedidos_de_reparacion = $id_pedido;
";

$result = mysqli_query($conn, $query_factura);

$total = 0;

echo "<h1>Factura de Reparación</h1>";

if ($row = mysqli_fetch_assoc($result)) {
    echo "<h2>Cliente: " . $row['nombre'] . " " . $row['apellido'] . "</h2>";
    echo "<p>CUIT: " . $row['cuit'] . "</p>";
    echo "<p>Dirección: " . $row['direccion'] . "</p>";
    echo "<p>Número de orden: " . $row['numero_orden'] . "</p>";
    
    echo "<h3>Detalles de Reparación</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Descripción Reparación</th><th>Servicio</th><th>Precio</th></tr>";
    
    // Mostrar los detalles de los servicios en la tabla
    do {
        echo "<tr>";
        echo "<td>" . $row['descripcion_reparacion'] . "</td>";
        echo "<td>" . $row['servicio_descripcion'] . "</td>";
        echo "<td>" . $row['precio_servicio'] . "</td>";
        echo "</tr>";
        
        // Sumar el precio al total
        $total += $row['precio_servicio'];
    } while ($row = mysqli_fetch_assoc($result));
    
    echo "</table>";
    
    // Mostrar el total de la factura
    echo "<h3>Total: $" . $total . "</h3>";
} else {
    echo "No se encontraron detalles de la reparación.";
}

?>