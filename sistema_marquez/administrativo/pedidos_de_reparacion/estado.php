<?php
include_once '../base_datos/db.php'; // Incluir la conexión a la base de datos

// Obtener los datos del formulario con seguridad
$order_number = isset($_POST['order_number']) ? htmlspecialchars(trim($_POST['order_number'])) : '';
$dni = isset($_POST['dni']) ? htmlspecialchars(trim($_POST['dni'])) : '';

// Inicializar la variable para la sentencia preparada
$stmt = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($order_number) && !empty($dni)) {
        // Preparar la consulta para verificar la orden y el cliente
        $stmt = $conn->prepare("
            SELECT pr.id_pedido_reparacion, pr.numero_orden, dr.estado_dispositivo, c.nombre, c.apellido
            FROM pedidos_de_reparacion pr
            JOIN detalle_reparaciones dr ON pr.id_pedido_reparacion = dr.id_pedido_reparacion
            JOIN clientes c ON pr.id_clientes = c.id_clientes
            WHERE pr.numero_orden = ? AND c.dni = ?
        ");

        if ($stmt) {
            // Asignar los parámetros
            $stmt->bind_param('ss', $order_number, $dni);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Obtener los datos de la consulta
                $order_info = $result->fetch_assoc();

                // Mostrar la información obtenida
                echo "<div class='alert alert-info'>";
                echo "<p><strong>Número de Orden:</strong> " . htmlspecialchars($order_info['numero_orden']) . "</p>";
                echo "<p><strong>Nombre del Cliente:</strong> " . htmlspecialchars($order_info['nombre']) . " " . htmlspecialchars($order_info['apellido']) . "</p>";
                echo "<p><strong>Estado del Dispositivo:</strong> " . htmlspecialchars($order_info['estado_dispositivo']) . "</p>";
                echo "</div>";
            } else {
                // Mostrar un mensaje si no se encuentra la información
                echo "<div class='alert alert-warning'>No se encontró ninguna información con el número de orden y DNI proporcionados.</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error al preparar la consulta.</div>";
        }
    } else {
        // Mostrar un mensaje si faltan datos en el formulario
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
        /* Estilos personalizados aquí */
        .alert-info { padding: 1px 15px; margin-bottom: 10px; border-radius: 8px; }
        .alert-info p { font-size: 0.9rem; margin: 1px 0; }
        .alert-info strong { font-size: 1.1rem; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); max-width: 600px; margin: 30px auto; border: 1px solid #d3d3d3; }
        h2.text-center { font-size: 2rem; color: #333; font-weight: bold; margin-bottom: 30px; text-transform: uppercase; text-align: center; letter-spacing: 1px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        form { margin-top: 20px; }
        .btn-secondary { background-color: #6c757d; padding: 10px 20px; display: block; margin: 20px auto 0; border-radius: 8px; }
        .btn-secondary:hover { background-color: #5a6268; }
        .semaforo { display: flex; justify-content: space-between; max-width: 300px; margin: 20px auto; padding: 10px; border-radius: 10px; background-color: #f8f9fa; }
        .semaforo div { width: 40px; height: 40px; border-radius: 50%; border: 3px solid #000; }
        .indicator-red { background-color: red; }
        .indicator-yellow { background-color: yellow; }
        .indicator-green { background-color: green; }
        .indicator-blue { background-color: blue; }
        .indicator-lightblue { background-color: lightblue; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Consulta de Estado</h2>
    
    <!-- Formulario para ingresar los datos -->
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

    <!-- Barra de semáforo -->
    <div class="semaforo">
        <div class="indicator-red"></div>
        <div class="indicator-yellow"></div>
        <div class="indicator-green"></div>
        <div class="indicator-blue"></div>
        <div class="indicator-lightblue"></div>
    </div>

    <!-- Botón de Volver -->
    <div class="mt-4">
        <a href="../index.html" class="btn btn-secondary">Volver</a>
    </div>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.com/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>