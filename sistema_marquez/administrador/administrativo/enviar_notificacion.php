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
                $id_cliente = $row['id_clientes'];

                // Mensaje de notificación
                $mensaje = "Estimado $nombre,\n\nSu pedido con número $numero_orden ha sido completado.\nGracias por confiar en nosotros.\n\nSaludos,\nMi Empresa";

                // Enviar notificación según el canal seleccionado
                if ($canal == 'correo') {
                    // Enviar notificación por correo
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp-mail.outlook.com '; // Cambia esto por tu servidor SMTP
                        $mail->SMTPAuth = true;
                        $mail->Username = 'issmael11@hotmail.com'; // Cambia esto por tu usuario SMTP
                        $mail->Password = 'leamsi476235'; // Cambia esto por tu contraseña SMTP
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
                    VALUES (?, ?, NOW(), 'enviado', ?)
                ";
                if ($stmt_insert = $conn->prepare($sql_insert)) {
                    $stmt_insert->bind_param("sss", $id_cliente, $mensaje, $numero_orden);
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos y Clientes</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
    <style>
        .btn-back {
            margin-top: 20px;
        }
        .success-message {
            color: #28a745;
            font-weight: bold;
        }
        .error-message {
            color: #dc3545;
            font-weight: bold;
        }
        .info-message {
            color: #17a2b8;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Gestión de Pedidos y Clientes</h1>

    <button onclick="window.location.href='../administrador/gestionar_tareas.php';" class="btn btn-secondary btn-back">Volver Atrás</button>

    <h2>Pedidos de Reparación</h2>
    <?php if ($result_pedidos->num_rows > 0): ?>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Dispositivo</th>
                    <th>Fecha de Pedido</th>
                    <th>Estado</th>
                    <th>Número de Orden</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_pedidos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($row['marca']) . ' ' . htmlspecialchars($row['modelo']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_de_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        <td><?php echo htmlspecialchars($row['numero_orden']); ?></td>
                        <td>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="pedido_id" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>">
                                <input type="hidden" name="canal" value="correo">
                                <input type="submit" name="enviar_notificacion" value="Enviar Notificación" class="btn btn-primary btn-sm">
                            </form>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>">
                                <input type="hidden" name="tabla" value="pedidos_de_reparacion">
                                <input type="submit" name="editar" value="Editar" class="btn btn-warning btn-sm">
                            </form>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>">
                                <input type="hidden" name="tabla" value="pedidos_de_reparacion">
                                <input type="submit" name="copiar" value="Copiar" class="btn btn-info btn-sm">
                            </form>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_pedidos_de_reparacion']); ?>">
                                <input type="hidden" name="tabla" value="pedidos_de_reparacion">
                                <input type="submit" name="borrar" value="Borrar" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas borrar este pedido?');">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay pedidos registrados.</p>
    <?php endif; ?>

    <h2>Clientes</h2>
    <?php if ($result_clientes->num_rows > 0): ?>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Correo Electrónico</th>
                    <th>Dirección</th>
                    <th>DNI</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_clientes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_clientes']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($row['correo_electronico']); ?></td>
                        <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($row['dni']); ?></td>
                        <td>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_clientes']); ?>">
                                <input type="hidden" name="tabla" value="clientes">
                                <input type="submit" name="editar" value="Editar" class="btn btn-warning btn-sm">
                            </form>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_clientes']); ?>">
                                <input type="hidden" name="tabla" value="clientes">
                                <input type="submit" name="copiar" value="Copiar" class="btn btn-info btn-sm">
                            </form>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_clientes']); ?>">
                                <input type="hidden" name="tabla" value="clientes">
                                <input type="submit" name="borrar" value="Borrar" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas borrar este cliente?');">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay clientes registrados.</p>
    <?php endif; ?>
</div>

<script src="path/to/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
