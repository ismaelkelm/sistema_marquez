<?php
include_once '../base_datos/db.php'; // Incluir la conexión a la base de datos
// Iniciar la sesión si no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtener los datos del formulario con seguridad
$order_number = isset($_POST['order_number']) ? htmlspecialchars(trim($_POST['order_number'])) : '';
$dni = isset($_POST['dni']) ? htmlspecialchars(trim($_POST['dni'])) : '';

$stmt = null;

header('Content-Type: application/json'); // Establecer el tipo de contenido a JSON

$response = []; // Array para almacenar la respuesta

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($order_number) && !empty($dni)) {
        // Preparar la consulta para obtener el detalle más reciente de cada dispositivo
        $stmt = $conn->prepare("
            SELECT pr.numero_orden, dr.estado_dispositivo, d.marca, d.modelo, c.nombre, c.apellido, dr.fecha_seguimiento
            FROM pedidos_de_reparacion pr
            JOIN clientes c ON pr.id_clientes = c.id_clientes
            JOIN detalle_reparaciones dr ON pr.id_pedidos_de_reparacion = dr.id_pedidos_de_reparacion
            JOIN dispositivos d ON dr.id_dispositivos = d.id_dispositivos
            WHERE pr.numero_orden = ? AND c.dni = ?
            AND dr.fecha_seguimiento = (
                SELECT MAX(dr2.fecha_seguimiento)
                FROM detalle_reparaciones dr2
                WHERE dr2.id_dispositivos = dr.id_dispositivos
            )
        ");

        if ($stmt) {
            $stmt->bind_param('ss', $order_number, $dni);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Almacenar la información en el array de respuesta
                while ($order_info = $result->fetch_assoc()) {
                    // Determinar el estado del dispositivo
                    $estado = (int)$order_info['estado_dispositivo'];
                    switch ($estado) {
                        case 0:
                            $estadoDispositivo = "Pendiente de revisión";
                            break;
                        case 1:
                            $estadoDispositivo = "Reparado";
                            break;
                        case 2:
                            $estadoDispositivo = "En Proceso";
                            break;
                        case 3:
                            $estadoDispositivo = "Cancelado";
                            break;
                        default:
                            $estadoDispositivo = "Estado desconocido";
                            break;
                    }

                    $response[] = [
                        'numero_orden' => htmlspecialchars($order_info['numero_orden']),
                        'nombre' => htmlspecialchars($order_info['nombre']),
                        'apellido' => htmlspecialchars($order_info['apellido']),
                        'dispositivo' => htmlspecialchars($order_info['marca'] . ' ' . $order_info['modelo']),
                        'estado_dispositivo' => $estadoDispositivo,
                        'fecha_seguimiento' => htmlspecialchars($order_info['fecha_seguimiento']),
                    ];
                }
            } else {
                $response = ['error' => 'No se encontró ninguna información con el número de orden y DNI proporcionados.'];
            }
            $stmt->close();
        } else {
            $response = ['error' => 'Error al preparar la consulta.'];
        }
    } else {
        $response = ['error' => 'Por favor, ingrese el número de orden y el DNI.'];
    }
}

// Enviar la respuesta JSON
echo json_encode($response);
?>








