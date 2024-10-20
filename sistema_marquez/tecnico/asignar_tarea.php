<?php
// Incluir el archivo de conexión a la base de datos
include '../base_datos/db.php';

// Variable para almacenar el mensaje de estado
$message = "";
$message_class = "";
$icon = ""; // Para el icono de check o X

// Verificar si se recibieron los datos necesarios
if (isset($_POST['id_tecnico']) && isset($_POST['id_dispositivos'])) {
    
    // Obtener los valores enviados desde el formulario
    $id_tecnico = $_POST['id_tecnico'];
    $id_dispositivo = $_POST['id_dispositivos'];

    // Preparar la consulta para seleccionar el detalle más antiguo basado en el id_dispositivo
    $query_select = "SELECT * FROM detalle_reparaciones 
                     WHERE id_dispositivos = ? 
                     ORDER BY fecha_seguimiento ASC LIMIT 1"; 

    $stmt_select = $conn->prepare($query_select);

    // Verificar si la consulta se preparó correctamente
    if ($stmt_select) {
        // Vincular el parámetro a la consulta
        $stmt_select->bind_param("i", $id_dispositivo);
        $stmt_select->execute();
        $result = $stmt_select->get_result(); // Obtener el resultado

        // Verificar si se encontró un detalle de reparación para ese dispositivo
        if ($result->num_rows > 0) {
            $detalle = $result->fetch_assoc(); // Obtener el detalle más antiguo

            // Preparar la consulta para insertar un nuevo detalle
            $query_insert = "INSERT INTO detalle_reparaciones 
                             (fecha_seguimiento, descripcion, estado_dispositivo, id_pedidos_de_reparacion, id_servicios, id_dispositivos, id_tecnico, id_accesorio, cantidad_usada) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Obtener los valores de la fila seleccionada
            $fecha_seguimiento = date('Y-m-d H:i:s'); // Nueva fecha para el nuevo detalle
            $descripcion = $detalle['descripcion']; 
            $estado_dispositivo = $detalle['estado_dispositivo']; 
            $id_pedidos_de_reparacion = $detalle['id_pedidos_de_reparacion']; 
            $id_servicios = $detalle['id_servicios']; 
            $id_accesorio = $detalle['id_accesorio']; 
            $cantidad_usada = $detalle['cantidad_usada']; 

            $stmt_insert = $conn->prepare($query_insert);

            // Verificar si la consulta se preparó correctamente
            if ($stmt_insert) {
                // Vincular los parámetros a la consulta
                $stmt_insert->bind_param("ssiiiiiis", $fecha_seguimiento, $descripcion, $estado_dispositivo, $id_pedidos_de_reparacion, $id_servicios, $id_dispositivo, $id_tecnico, $id_accesorio, $cantidad_usada);

                // Ejecutar la consulta
                if ($stmt_insert->execute()) {
                    $message = "Nuevo detalle insertado correctamente.";
                    $message_class = "success"; 
                    $icon = "✔️"; // Icono de check
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'gestionar_tareas.php';
                            }, 1500); 
                          </script>";
                } else {
                    $message = "Error al insertar el nuevo detalle: " . $stmt_insert->error;
                    $message_class = "error"; 
                    $icon = "❌"; // Icono de error
                }

                // Cerrar la consulta
                $stmt_insert->close();
            } else {
                $message = "Error al preparar la consulta de inserción: " . $conn->error;
                $message_class = "error"; 
                $icon = "❌"; 
            }
        } else {
            $message = "No se encontró ningún detalle de reparación para el dispositivo especificado.";
            $message_class = "error"; 
            $icon = "❌"; 
        }

        $stmt_select->close(); 
    } else {
        $message = "Error al preparar la consulta de selección: " . $conn->error;
        $message_class = "error"; 
        $icon = "❌"; 
    }
} else {
    $message = "Datos no recibidos correctamente.";
    $message_class = "error"; 
    $icon = "❌"; 
    if (!isset($_POST['id_tecnico'])) {
        $message .= " El ID técnico no fue recibido.";
    }
    if (!isset($_POST['id_dispositivos'])) {
        $message .= " El ID de dispositivo no fue recibido.";
    }
}

// Cerrar la conexión
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
