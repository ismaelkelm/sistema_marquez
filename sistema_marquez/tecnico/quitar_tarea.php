<?php
// Incluir el archivo de conexión a la base de datos
include '../base_datos/db.php';
// Establecer la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Variable para almacenar el mensaje de estado
$message = "";
$message_class = "";
$icon = ""; // Para el icono de check o X

// Verificar si el formulario fue enviado correctamente
if (isset($_POST['id_detalle_reparaciones'])) {
    
    // Obtener los datos enviados desde el formulario
    $id_detalle_reparaciones = $_POST['id_detalle_reparaciones'];

    // Obtener el detalle actual para conservar ciertos valores
    $query_select = "SELECT id_pedidos_de_reparacion, id_dispositivos FROM detalle_reparaciones WHERE id_detalle_reparaciones = ?";
    $stmt_select = $conn->prepare($query_select);

    // Verificar si la conexión a la base de datos fue exitosa
    if ($stmt_select === false) {
        $message = 'Error al preparar la consulta: ' . $conn->error;
        $message_class = "error";
        $icon = "❌";
    } else {
        $stmt_select->bind_param("i", $id_detalle_reparaciones);
        $stmt_select->execute();
        $result_select = $stmt_select->get_result();

        // Verificar si el detalle existe
        if ($result_select->num_rows > 0) {
            $detalle_actual = $result_select->fetch_assoc();

            // Establecer los valores modificados
            $descripcion = '--------';
            $estado_dispositivo = 0;
            $id_servicios = 1;
            $id_tecnico = 0;
            
            // Mantener el id_pedidos_de_reparacion y id_dispositivos del detalle original
            $id_pedidos_de_reparacion = $detalle_actual['id_pedidos_de_reparacion'];
            $id_dispositivos = $detalle_actual['id_dispositivos'];
            
            // Obtener la fecha y hora actual en formato DATETIME
            $fecha_seguimiento = date('Y-m-d H:i:s');
            $id_accesorio = 0;
            $cantidad = 0;
            
            // Insertar el nuevo detalle en la tabla `detalle_reparaciones`
            $query_insert = "
                INSERT INTO detalle_reparaciones 
                (fecha_seguimiento, descripcion, estado_dispositivo, id_pedidos_de_reparacion, id_servicios, id_dispositivos, id_tecnico, id_accesorio, cantidad_usada)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_insert = $conn->prepare($query_insert);

            // Verificar si la preparación fue exitosa
            if ($stmt_insert === false) {
                $message = 'Error al preparar la consulta de inserción: ' . $conn->error;
                $message_class = "error";
                $icon = "❌";
            } else {
                $stmt_insert->bind_param("ssiiiiiii", $fecha_seguimiento, $descripcion, $estado_dispositivo, $id_pedidos_de_reparacion, $id_servicios, $id_dispositivos, $id_tecnico, $id_accesorio, $cantidad);

                // Ejecutar la consulta
                if ($stmt_insert->execute()) {
                    $message = "La tarea ha sido quitada y un nuevo detalle fue insertado correctamente.";
                    $message_class = "success";
                    $icon = "✔️";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'tareas_pendientes.php';
                            }, 1500); 
                          </script>";
                } else {
                    $message = "Error al insertar el nuevo detalle: " . $stmt_insert->error;
                    $message_class = "error";
                    $icon = "❌";
                }

                // Cerrar la consulta de inserción
                $stmt_insert->close();
            }
        } else {
            $message = "No se encontró el detalle de reparación.";
            $message_class = "error";
            $icon = "❌";
        }

        // Cerrar la consulta de selección
        $stmt_select->close();
    }
} else {
    $message = "Datos no enviados correctamente.";
    $message_class = "error";
    $icon = "❌";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .message-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .message-box {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            max-width: 400px;
        }

        .success {
            color: green;
            border: 2px solid green;
        }

        .error {
            color: red;
            border: 2px solid red;
        }

        .message-box h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .message-box p {
            font-size: 24px;
        }

        .message-box i {
            font-size: 48px;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <div class="message-box <?php echo $message_class; ?>">
            <i><?php echo $icon; ?></i>
            <h1><?php echo $message_class == 'success' ? 'Éxito' : 'Error'; ?></h1>
            <p><?php echo $message; ?></p>
        </div>
    </div>
</body>
</html>
