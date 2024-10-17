<?php
// Incluir el archivo de conexión a la base de datos
include '../../base_datos/db.php';

if (isset($_POST['id_dispositivos']) && isset($_POST['id_detalle_reparaciones'])) {
    $id_dispositivos = $_POST['id_dispositivos'];
    $id_detalle_reparaciones = $_POST['id_detalle_reparaciones'];

    // Ahora puedes usar $id_dispositivos y $id_detalle_reparaciones en tu consulta SQL o lógica
} else {
    die("Faltan datos para continuar.");
}

// Iniciar la sesión y obtener el id_usuario
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['user_id'];

// Consulta SQL para obtener el último detalle del dispositivo basado en la fecha de seguimiento
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
    dr.id_servicios,
    dr.descripcion
FROM 
    detalle_reparaciones dr
INNER JOIN 
    pedidos_de_reparacion p 
    ON dr.id_pedidos_de_reparacion = p.id_pedidos_de_reparacion
INNER JOIN 
    dispositivos d 
    ON dr.id_dispositivos = d.id_dispositivos
WHERE 
    dr.id_dispositivos = ?
ORDER BY 
    dr.fecha_seguimiento DESC
LIMIT 1";

// Preparar la consulta
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_dispositivos);
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("No se encontró ningún detalle de reparación para este dispositivo.");
}

// Consulta para obtener los servicios
$servicios_query = "SELECT id_servicios, descripcion FROM servicios";
$servicios_result = $conn->query($servicios_query);

$servicios = [];
if ($servicios_result->num_rows > 0) {
    while ($servicio = $servicios_result->fetch_assoc()) {
        $servicios[$servicio['id_servicios']] = $servicio['descripcion'];
    }
}

// Definición de estados
$estados = [
    0 => "En Reparación",
    1 => "Reparado"
];

// Consulta para obtener accesorios y componentes
$query_accesorios_componentes = "SELECT id_accesorios_y_componentes, nombre, precio FROM accesorios_y_componentes";
$result_accesorios_componentes = mysqli_query($conn, $query_accesorios_componentes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Modificar Detalle de Reparación</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"], select {
            width: 100%;
        }
        button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .remove-button {
            background-color: #f44336; /* Rojo para quitar */
        }
    </style>
</head>
<body>

<h2>Modificar Detalle de Reparación</h2>

<form action="modificar_detalles.php" method="POST">
    <table>
        <tr>
            <th>Número de Orden</th>
            <th>Observación</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Estado del Dispositivo</th>
            <th>Servicio Actual</th>
            <th>Descripción</th>
        </tr>

        <tr>
            <td><?php echo $row['numero_orden']; ?></td>
            <td><?php echo $row['observacion']; ?></td>
            <td><?php echo $row['marca']; ?></td>
            <td><?php echo $row['modelo']; ?></td>

            <!-- Estado del Dispositivo -->
            <td>
                <select name='estado_dispositivo'>
                    <?php
                    foreach ($estados as $key => $estado) {
                        $selected = ($key == $row['estado_dispositivo']) ? "selected" : "";
                        echo "<option value='$key' $selected>$estado</option>";
                    }
                    ?>
                </select>
            </td>

            <!-- ID Servicios -->
            <td>
                <select name='id_servicios'>
                    <?php
                    foreach ($servicios as $id_servicio => $descripcion) {
                        $selected = ($id_servicio == $row['id_servicios']) ? "selected" : "";
                        echo "<option value='$id_servicio' $selected>$descripcion</option>";
                    }
                    ?>
                </select>
            </td>

            <!-- Descripción -->
            <td><input type="text" name="descripcion[]" value="<?php echo $row['descripcion']; ?>"></td>
        </tr>
    </table>

    <!-- Campos ocultos -->
    <input type="hidden" name="id_detalle_reparaciones" value="<?php echo $row['id_detalle_reparaciones']; ?>">
    <input type="hidden" name="id_pedidos_de_reparacion" value="<?php echo $row['id_pedidos_de_reparacion']; ?>">
    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario ?>">
    
    <br>
    <div id="detalles-accesorios">
        <div class="detalle-accesorio">
            <label>Accesorio:</label>
            <select name="id_accesorios_y_componentes[]">
                <?php
                if (mysqli_num_rows($result_accesorios_componentes) > 0) {
                    while ($row_accesorio = mysqli_fetch_assoc($result_accesorios_componentes)) {
                        echo '<option value="' . $row_accesorio['id_accesorios_y_componentes'] . '">' . $row_accesorio['nombre'] . '</option>';
                    }
                } else {
                    echo '<option value="">No hay accesorios disponibles</option>';
                }
                ?>
            </select>

            <label>Cantidad:</label>
            <input type="number" name="cantidad_usada[]" min="1" required>
        </div>
    </div>

    <button type="button" id="add-accesorio">Agregar otro accesorio</button>
    <br><br>
    <button type="submit">Modificar Detalles y Enviar</button>
</form>

<br>

<script>
$(document).ready(function() {
    $('#add-accesorio').on('click', function () {
        var newAccesorio = $('.detalle-accesorio:first').clone();
        newAccesorio.find('input').val(''); // Limpiar el campo de cantidad
        $('#detalles-accesorios').append(newAccesorio);
    });
});
</script>

</body>
</html>
