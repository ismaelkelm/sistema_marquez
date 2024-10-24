<?php
// Incluir el archivo de conexión a la base de datos
include '../base_datos/db.php';

// Iniciar la sesión y obtener el id_usuario
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['user_id'];
$query = "
SELECT 
    dr.id_detalle_reparaciones,
    p.id_pedidos_de_reparacion,  
    d.id_dispositivos,            
    p.numero_orden, 
    p.observacion, 
    d.marca, 
    d.modelo,
    dr.estado_dispositivo,
    MAX(dr.fecha_seguimiento) AS ultima_fecha_seguimiento
FROM 
    detalle_reparaciones dr
INNER JOIN 
    pedidos_de_reparacion p 
    ON dr.id_pedidos_de_reparacion = p.id_pedidos_de_reparacion
INNER JOIN 
    dispositivos d 
    ON dr.id_dispositivos = d.id_dispositivos
WHERE 
    dr.id_tecnico = ?
    AND dr.fecha_seguimiento = (
        SELECT MAX(dr2.fecha_seguimiento)
        FROM detalle_reparaciones dr2
        WHERE dr2.id_dispositivos = dr.id_dispositivos
    )
    AND dr.estado_dispositivo != 1  -- Solo mostrar si el estado del último seguimiento es distinto de 1 
GROUP BY 
    d.id_dispositivos
ORDER BY 
    ultima_fecha_seguimiento DESC
";


// Preparar la consulta
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();

// Definición de estados
$estados = [
    0 => "Pendiente",
    1 => "Reparado",
    2 => "En Proceso",
    3 => "Cancelado",
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reparaciones Asignadas</title>
    <link rel="stylesheet" href="./estilos/pendientes.css">  <!-- Enlace al archivo CSS -->
</head>
<body>
<button onclick="location.href='../tecnico/tecnico_panel.php'" class="button button-back">Volver</button>
<h2>Reparaciones Asignadas al Técnico</h2>

<?php
// Mostrar los resultados en una tabla
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>
            <th>Número de Orden</th>
            <th>Observación</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Estado del Dispositivo</th>
            <th>Ir a Tarea</th>
            <th>Quitar Tarea</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['numero_orden'] . "</td>";
        echo "<td>" . $row['observacion'] . "</td>";
        echo "<td>" . $row['marca'] . "</td>";
        echo "<td>" . $row['modelo'] . "</td>";
        
        // Estado del Dispositivo
        echo "<td>" . $estados[$row['estado_dispositivo']] . "</td>";

        if (!empty($row['id_detalle_reparaciones'])) {
            echo "<td>";
            echo "<form action='detalles_de_pedido.php' method='POST'>";
            echo "<input type='hidden' name='id_dispositivos' value='" . $row['id_dispositivos'] . "'>";
            echo "<input type='hidden' name='id_detalle_reparaciones' value='" . $row['id_detalle_reparaciones'] . "'>";
            echo "<button type='submit' class='ir-a-tarea'>Ir a Tarea</button>";
            echo "</form>";
            echo "</td>";
        } else {
            echo "<td>Error: id_detalle_reparaciones no disponible</td>";
        }

        // Botón "Quitar Tarea"
        echo "<td>";
        echo "<form action='quitar_tarea.php' method='POST'>";
        echo "<input type='hidden' name='id_detalle_reparaciones' value='" . $row['id_detalle_reparaciones'] . "'>";
        echo "<button type='submit' class='quitar-tarea'>Quitar Tarea</button>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<h3>No se encontraron reparaciones asignadas al técnico.</h3>";
}
?>

</body>
</html>
