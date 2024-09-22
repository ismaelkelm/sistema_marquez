<?php
// Incluir el archivo de conexión a la base de datos
require_once '../base_datos/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegúrate de incluir PHPMailer
require '../vendor/autoload.php';

// Manejar acciones de formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['enviar_notificacion']) && isset($_POST['pedido_id']) && isset($_POST['canal'])) {
        $pedido_id = $_POST['pedido_id'];
        $canal = $_POST['canal'];

        // Consultar detalles del pedido y del cliente
        $sql = "
            SELECT pr.numero_orden, pr.estado, c.nombre, c.correo_electronico, c.telefono, c.id_clientes
            FROM pedidos_de_reparacion pr
            JOIN clientes c ON pr.id_clientes = c.id_clientes
            WHERE pr.id_pedidos_de_reparacion = ?
        ";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $pedido_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $nombre = $row['nombre'];
                $correo = $row['correo_electronico'];
                $telefono = $row['telefono'];
                $numero_orden = $row['numero_orden'];
                $estado = $row['estado'];
                $id_cliente = $row['id_clientes'];

                // Mensaje de notificación
                $mensaje = "Estimado $nombre,\n\nSu pedido con número de orden $numero_orden ha sido completado con el estado '$estado'.\nGracias por confiar en nosotros.\n\nSaludos,\nMi Empresa";

                // Enviar notificación según el canal seleccionado
                if ($canal == 'correo') {
                    // Enviar notificación por correo
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.office365.com'; // Servidor SMTP de Hotmail/Outlook
                        $mail->SMTPAuth = true;
                        $mail->Username = 'issmael11@hotmail.com'; // Tu dirección de correo de Hotmail
                        $mail->Password = 'leamsi476235'; // Tu contraseña de Hotmail
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('no-reply@example.com', 'Tu Empresa');
                        $mail->addAddress($correo, $nombre);

                        $mail->isHTML(false);
                        $mail->Subject = 'Notificación de Pedido Completado';
                        $mail->Body    = $mensaje;

                        $mail->send();
                        echo "<p class='success-message'>Correo enviado a $correo</p>";
                    } catch (Exception $e) {
                        echo "<p class='error-message'>Error al enviar el correo: " . htmlspecialchars($mail->ErrorInfo) . "</p>";
                    }
                } elseif ($canal == 'telefono') {
                    // Enviar notificación por SMS (simulado aquí)
                    echo "<p class='info-message'>Notificación enviada al teléfono $telefono: $mensaje</p>";
                } elseif ($canal == 'whatsapp') {
                    // Enviar notificación por WhatsApp (simulado aquí)
                    echo "<p class='info-message'>Notificación enviada a WhatsApp $telefono: $mensaje</p>";
                }

                // Insertar la notificación en la base de datos
                $sql_insert = "
                    INSERT INTO notificaciones (id_usuarios, mensaje, fecha_de_envío, estado, numero_orden)
                    VALUES (?, ?, CURDATE(), ?, ?)
                ";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("ssss", $id_cliente, $mensaje, $estado, $numero_orden);
                    $stmt_insert->execute();
                    echo "<p class='success-message'>Notificación insertada en la base de datos</p>";
                } else {
                    echo "<p class='error-message'>Error en la preparación de la consulta de inserción: " . htmlspecialchars($conn->error) . "</p>";
                }
            } else {
                echo "<p class='error-message'>No se encontró el pedido con ID $pedido_id</p>";
            }
        } else {
            echo "<p class='error-message'>Error en la preparación de la consulta SQL: " . htmlspecialchars($conn->error) . "</p>";
        }
    } elseif (isset($_POST['editar'])) {
        // Redirigir a la página de edición
        $id = $_POST['id'];
        $tabla = $_POST['tabla'];
        header("Location: editar.php?id=$id&tabla=$tabla");
        exit();
    } elseif (isset($_POST['copiar'])) {
        // Redirigir a la página de copia
        $id = $_POST['id'];
        $tabla = $_POST['tabla'];
        header("Location: copiar.php?id=$id&tabla=$tabla");
        exit();
    } elseif (isset($_POST['borrar'])) {
        // Borrar registro
        $id = $_POST['id'];
        $tabla = $_POST['tabla'];
        $sql_delete = "DELETE FROM $tabla WHERE id_" . substr($tabla, 0, -1) . " = ?";
        if ($stmt = $conn->prepare($sql_delete)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<p class='success-message'>Registro borrado correctamente.</p>";
            } else {
                echo "<p class='error-message'>Error al borrar el registro: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='error-message'>Error en la preparación de la consulta de borrado: " . htmlspecialchars($conn->error) . "</p>";
        }
    }
}

// Consultar todos los pedidos de reparación
$sql_pedidos = "SELECT pr.id_pedidos_de_reparacion, pr.fecha_de_pedido, pr.estado, pr.numero_orden, c.nombre AS nombre_cliente, d.marca, d.modelo, d.numero_de_serie
                FROM pedidos_de_reparacion pr
                JOIN clientes c ON pr.id_clientes = c.id_clientes
                JOIN dispositivos d ON pr.id_dispositivos = d.id_dispositivos
                ORDER BY pr.numero_orden ASC";
$result_pedidos = $conn->query($sql_pedidos);

// Consultar todos los clientes
$sql_clientes = "SELECT * FROM clientes ORDER BY nombre ASC";
$result_clientes = $conn->query($sql_clientes);
?>
