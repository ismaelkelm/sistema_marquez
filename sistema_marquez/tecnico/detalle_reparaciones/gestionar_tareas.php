<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php';

// Iniciar la sesión y obtener el id_usuario
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['user_id'];

// Inicializar la variable para la fecha
$fechaFiltrada = '';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener la fecha del formulario
    $fechaFiltrada = $_POST['fecha_de_pedido'];
}

// Preparar la consulta SQL para obtener el rol del usuario
$sqlRol = "SELECT id_roles FROM usuario WHERE id_usuario = '$id_usuario'";
$resultRol = $conn->query($sqlRol);

// Verificar si se obtuvo el rol
if ($resultRol->num_rows > 0) {
    $rowRol = $resultRol->fetch_assoc();
    $rol_usuario = $rowRol['id_roles'];
} else {
    echo "No se encontró el rol del usuario.";
    exit; // Salir si no se encuentra el rol
}

$sql = "
    SELECT dr.id_detalle_reparaciones, dr.id_dispositivos, dr.estado_dispositivo, pr.observacion, pr.numero_orden, dr.id_tecnico, di.marca
    FROM detalle_reparaciones dr
    JOIN pedidos_de_reparacion pr ON dr.id_pedidos_de_reparacion = pr.id_pedidos_de_reparacion
    JOIN dispositivos di ON dr.id_dispositivos = di.id_dispositivos
    WHERE dr.estado_dispositivo = 0
    AND dr.id_detalle_reparaciones = (
        SELECT MAX(dr2.id_detalle_reparaciones)
        FROM detalle_reparaciones dr2
        WHERE dr2.id_dispositivos = dr.id_dispositivos
    )
";

// Aplicar condiciones adicionales si existen
$conditions = [];
if (!empty($fechaFiltrada)) {
    $conditions[] = "pr.fecha_de_pedido = '$fechaFiltrada'";
}

if ($rol_usuario == 3) {
    // Mostrar solo detalles sin asignar (técnico = 0)
    $conditions[] = "dr.id_tecnico = 0";
}

// Añadir las condiciones a la consulta
if (count($conditions) > 0) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY dr.id_detalle_reparaciones DESC"; // Ordenar por el detalle más reciente

$result = $conn->query($sql);

// Generar el formulario HTML para filtrar por fecha
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Detalle de Reparaciones</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button {
            margin: 10px 0;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>GESTION DE TAREAS PENDIENTES</h1>
<button onclick="history.back();" class="button">Volver</button>

<!-- Formulario para filtrar por fecha -->
<form method="POST" action="">
    <label for="fecha_de_pedido">Selecciona la fecha del pedido:</label>
    <input type="date" name="fecha_de_pedido" value="<?php echo htmlspecialchars($fechaFiltrada); ?>">
    <input type="submit" value="Filtrar">
</form>

<!-- Mostrar el ID de usuario -->
<p>Usuario: <?php echo htmlspecialchars($id_usuario); ?></p>

<!-- Mostrar los detalles de las reparaciones -->
<?php
// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Iniciar la tabla HTML
    echo "<table>";
    echo "<tr><th>Estado Dispositivo</th><th>Marca</th><th>Observación</th><th>Número de Orden</th><th>ID Técnico</th><th>Acciones</th></tr>";
    
    // Recorrer los resultados y mostrarlos en la tabla
    while ($row = $result->fetch_assoc()) {
        // Cambiar el estado del dispositivo a "Pendiente de revisión" si es 0
        $estadoDispositivo = ($row['estado_dispositivo'] == 0) ? "Pendiente de revisión" : $row['estado_dispositivo'];
        $tecnicoAsignado = ($row['id_tecnico'] == 0) ? "Sin Asignar" : $row['id_tecnico'];

        echo "<tr>";
        echo "<td>" . htmlspecialchars($estadoDispositivo) . "</td>";
        echo "<td>" . htmlspecialchars($row['marca']) . "</td>";
        echo "<td>" . htmlspecialchars($row['observacion']) . "</td>";
        echo "<td>" . htmlspecialchars($row['numero_orden']) . "</td>";
        echo "<td>" . htmlspecialchars($tecnicoAsignado) . "</td>";
        
        // Mostrar el formulario correspondiente según el rol del usuario
        echo '<td>';
        if ($rol_usuario == 2) {
            // Mostrar select para rol 2
            // Obtener todos los técnicos
            $sqlTecnicos = "SELECT id_usuario, nombre FROM usuario WHERE id_roles = 3";
            $resultTecnicos = $conn->query($sqlTecnicos);
            
            echo '<form method="POST" action="asignar_tarea.php">';
            echo '<label for="tecnico">Selecciona un técnico:</label>';
            echo '<select name="id_tecnico" required>'; // Cambiado a "id_tecnico"
            echo '<option value="0" ' . ($tecnicoAsignado == "Sin Asignar" ? 'selected' : '') . '>Sin Asignar</option>';
            
            // Llenar el select con los técnicos disponibles
            while ($rowTecnico = $resultTecnicos->fetch_assoc()) {
                // Marcar el técnico asignado como seleccionado
                $selected = ($row['id_tecnico'] == $rowTecnico['id_usuario']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($rowTecnico['id_usuario']) . '" ' . $selected . '>' . htmlspecialchars($rowTecnico['nombre']) . '</option>';
            }
            echo '</select>';
            echo '<input type="hidden" name="id_detalle_reparacion" value="' . htmlspecialchars($row['id_detalle_reparaciones']) . '">';
            
            // Ya no necesitas incluir el ID del usuario aquí
            echo '<button type="submit" class="button">Asignar Tarea</button>';
            echo '</form>';
        }elseif ($rol_usuario == 3) {
            // Solo mostrar botón "Asignarme tarea" para rol 3
            echo '<form method="POST" action="asignar_tarea.php">';
            echo '<input type="hidden" name="id_detalle_reparacion" value="' . htmlspecialchars($row['id_detalle_reparaciones']) . '">';
            echo '<input type="hidden" name="id_tecnico" value="' . htmlspecialchars($id_usuario) . '">'; // Enviar el ID del usuario actual
            echo '<button type="submit" class="button">Asignarme Tarea</button>';
            echo '</form>';
        
        } elseif ($rol_usuario == 3) {
            // Solo mostrar botón "Asignarme tarea" para rol 3
            echo '<form method="POST" action="asignar_tarea.php">';
            echo '<input type="hidden" name="id_detalle_reparacion" value="' . htmlspecialchars($row['id_detalle_reparaciones']) . '">';
            echo '<input type="hidden" name="id_tecnico" value="' . htmlspecialchars($id_usuario) . '">'; // Enviar el ID del usuario actual
            echo '<button type="submit" class="button">Asignarme Tarea</button>';
            echo '</form>';
        }
        echo '</td>';
        
        echo "</tr>";
    }
    
    // Cerrar la tabla HTML
    echo "</table>";
} else {
    echo "No se encontraron registros.";
}
// Cerrar la conexión
$conn->close();
?>
</body>
</html>