<?php
// Incluir el archivo de conexión a la base de datos
require_once '../base_datos/db.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pedido_id'])) {
    $pedido_id = $_POST['pedido_id'];
    echo "Pedido ID recibido: $pedido_id<br>"; // Mensaje de depuración

    // Consultar los detalles del pedido y del cliente
    $sql = "
        SELECT pr.numero_pedido, pr.estado, c.nombre, c.correo_electronico
        FROM pedidos_de_reparacion pr
        JOIN clientes c ON pr.numero_pedido = c.numero_pedido
        WHERE pr.id_pedidos_de_reparacion = ?
    ";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $nombre = $row['nombre'];
            $correo = $row['correo_electronico'];
            $numero_pedido = $row['numero_pedido'];

            // Enviar notificación
            $asunto = "Notificación de Pedido Completado";
            $mensaje = "Estimado $nombre,\n\nSu pedido con número $numero_pedido ha sido completado.\nGracias por confiar en nosotros.\n\nSaludos,\nMi Empresa";
            $cabeceras = "From: no-reply@miempresa.com";

            // Enviar el correo
            if (mail($correo, $asunto, $mensaje, $cabeceras)) {
                echo "Correo enviado a $correo<br>"; // Mensaje de depuración

                // Insertar la notificación en la base de datos
                $sql_insert = "
                    INSERT INTO notificaciones (id_usuarios, mensaje, fecha_de_envio, estado, numero_pedido)
                    VALUES ((SELECT id_usuarios FROM clientes WHERE numero_pedido = ?), ?, NOW(), 'enviado', ?)
                ";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $mensaje = "Su pedido con número $numero_pedido ha sido completado.";
                    $stmt_insert->bind_param("sss", $numero_pedido, $mensaje, $numero_pedido);
                    $stmt_insert->execute();
                    echo "Notificación insertada en la base de datos<br>"; // Mensaje de depuración
                } else {
                    echo "Error en la preparación de la consulta de inserción<br>";
                }

                // Actualizar el estado de la notificación en la base de datos
                $sql_update = "
                    UPDATE notificaciones
                    SET estado = 'enviado'
                    WHERE numero_pedido = ? AND estado = 'pendiente'
                ";
                if ($stmt_update = $conn->prepare($sql_update)) {
                    $stmt_update->bind_param("s", $numero_pedido);
                    $stmt_update->execute();
                    echo "Estado de notificación actualizado<br>"; // Mensaje de depuración
                } else {
                    echo "Error en la preparación de la consulta de actualización<br>";
                }
            } else {
                echo "Error al enviar el correo<br>";
            }
        } else {
            echo "No se encontró el pedido con ID $pedido_id<br>";
        }
    } else {
        echo "Error en la preparación de la consulta SQL<br>";
    }
    
    // Redirigir o mostrar un mensaje de éxito
    header('Location: administrador.php?mensaje=notificacion_enviada');
    exit();
}

// Cerrar la conexión
$conn->close();
?>
