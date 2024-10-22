<?php
include_once '../base_datos/db.php'; // Incluir la conexión a la base de datos

// Obtener los datos del formulario con seguridad
$order_number = isset($_POST['order_number']) ? htmlspecialchars(trim($_POST['order_number'])) : '';
$dni = isset($_POST['dni']) ? htmlspecialchars(trim($_POST['dni'])) : '';

$stmt = null;

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
                // Mostrar la información obtenida para cada dispositivo
                while ($order_info = $result->fetch_assoc()) {
                    // Determinar el estado del dispositivo
                    $estado = (int)$order_info['estado_dispositivo'];
                    switch ($estado) {
                        case 0:
                            $estadoDispositivo = "Pendiente de revisión";
                            $colorSemaforo = 'indicator-red';
                            $resaltar = 'highlight'; // Resaltar este estado
                            break;
                        case 1:
                            $estadoDispositivo = "Reparado";
                            $colorSemaforo = 'indicator-green';
                            $resaltar = 'highlight'; // Resaltar este estado
                            break;
                        case 2:
                            $estadoDispositivo = "En Proceso";
                            $colorSemaforo = 'indicator-yellow';
                            $resaltar = 'highlight'; // Resaltar este estado
                            break;
                        case 3:
                            $estadoDispositivo = "Cancelado";
                            $colorSemaforo = 'indicator-blue';
                            $resaltar = 'highlight'; // Resaltar este estado
                            break;
                        default:
                            $estadoDispositivo = "Estado desconocido";
                            $colorSemaforo = 'indicator-red'; // Default a rojo si no se conoce
                            $resaltar = ''; // No resaltar
                            break;
                    }

                    echo "<div class='alert alert-info'>";
                    echo "<p><strong>Número de Orden:</strong> " . htmlspecialchars($order_info['numero_orden']) . "</p>";
                    echo "<p><strong>Nombre del Cliente:</strong> " . htmlspecialchars($order_info['nombre']) . " " . htmlspecialchars($order_info['apellido']) . "</p>";
                    echo "<p><strong>Dispositivo:</strong> " . htmlspecialchars($order_info['marca']) . " " . htmlspecialchars($order_info['modelo']) . "</p>";
                    echo "<p><strong>Estado del Dispositivo:</strong> $estadoDispositivo</p>";
                    echo "<p><strong>Fecha del Seguimiento:</strong> " . htmlspecialchars($order_info['fecha_seguimiento']) . "</p>";
                    echo "</div>";

                    // Mostrar semáforo para cada dispositivo
                    echo "<div class='semaforo'>
                    <div class='indicator-red " . ($estado === 0 ? 'highlight' : '') . "' style='width: 60px; height: 60px;'></div>
                    <div class='indicator-yellow " . ($estado === 2 ? 'highlight' : '') . "' style='width: 60px; height: 60px;'></div>
                    <div class='indicator-green " . ($estado === 1 ? 'highlight' : '') . "' style='width: 60px; height: 60px;'></div>
                    <div class='indicator-blue " . ($estado === 3 ? 'highlight' : '') . "' style='width: 60px; height: 60px;'></div>
                    </div>";

                }
            } else {
                echo "<div class='alert alert-warning'>No se encontró ninguna información con el número de orden y DNI proporcionados.</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error al preparar la consulta.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Por favor, ingrese el número de orden y el DNI.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Estado - Mi Empresa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
                    /* Estilo para el body */
        body {
            background-color: #d8e9ff; /* Fondo suave azul claro */
            color: #333; /* Color de texto estándar */
        }
            /* Estilos personalizados */
            .alert-info { padding: 1px 15px; margin-bottom: 10px; border-radius: 8px; }
            .alert-info p { font-size: 0.9rem; margin: 1px 0; }
            .alert-info strong { font-size: 1.1rem; }
            .container { background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); max-width: 600px; margin: 30px auto; border: 1px solid #d3d3d3; }
            h2.text-center { font-size: 2rem; color: #333; font-weight: bold; margin-bottom: 30px; text-transform: uppercase; text-align: center; letter-spacing: 1px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
            form { margin-top: 20px; }
            .btn-secondary { background-color: #6c757d; padding: 10px 20px; display: block; margin: 20px auto 0; border-radius: 8px; }
            .btn-secondary:hover { background-color: #5a6268; }
            .semaforo { display: flex; justify-content: center; align-items: center; margin: 20px auto; padding: 10px; border-radius: 10px; background-color: #d8e9ff; }
            .indicator-red, .indicator-yellow, .indicator-green, .indicator-blue { border-radius: 50%; transition: transform 0.2s; }
            .indicator-red { background-color: red; }
            .indicator-yellow { background-color: yellow; }
            .indicator-green { background-color: green; }
            .indicator-blue { background-color: blue; }
            .highlight { 
                animation: pulsate 1s infinite; /* Añadir la animación de pulso */
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.3); 
            } 
            @keyframes pulsate {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }

    </style>

</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Consulta de Estado</h2>

    <form action="" method="POST" class="mb-4">
        <div class="form-group">
            <label for="order_number">Número de Orden:</label>
            <input type="text" id="order_number" name="order_number" class="form-control" placeholder="ORD" required onfocus="addPrefix()" oninput="addPrefix()" maxlength="10">
        </div>

        <div class="form-group">
            <label for="dni">Número de DNI del Cliente:</label>
            <input type="text" id="dni" name="dni" class="form-control" placeholder="Ingrese el DNI del cliente" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Consultar</button>
    </form>

    <script>
        function addPrefix() {
            const orderInput = document.getElementById('order_number');
            const prefix = 'ORD';
            
            if (!orderInput.value.startsWith(prefix)) {
                orderInput.value = prefix + orderInput.value;
            }
        }
    </script>

        <?php
        // Iniciar la sesión si no ha sido iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario ha iniciado sesión
        if (isset($_SESSION['user_id'])) {
            // Si el usuario ha iniciado sesión, muestra el botón que redirige a "cliente.php"
            echo '
            <div class="mt-4">
                <a href="cliente.php" class="btn btn-secondary">Volver</a>
            </div>';
        } else {
            // Si el usuario no ha iniciado sesión, muestra el botón que redirige a "index.html"
            echo '
            <div class="mt-4">
                <a href="../index.html" class="btn btn-secondary">Volver</a>
            </div>';
        }
        ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
