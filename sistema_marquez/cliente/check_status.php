<?php
include_once '../base_datos/db.php';

// Obtener los datos del formulario
$order_number = isset($_POST['order_number']) ? htmlspecialchars(trim($_POST['order_number'])) : '';
$dni = isset($_POST['dni']) ? htmlspecialchars(trim($_POST['dni'])) : '';
$customer_name = isset($_POST['customer_name']) ? htmlspecialchars(trim($_POST['customer_name'])) : '';
$customer_lastname = isset($_POST['customer_lastname']) ? htmlspecialchars(trim($_POST['customer_lastname'])) : '';

$stmt = null; // Definir la variable $stmt para evitar errores

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($order_number) && !empty($dni)) {
        // Preparar la consulta
        $stmt = $conn->prepare("
            SELECT pr.numero_orden, pr.estado_reparacion,pr.id_clientes,c.id_clientes, c.nombre, c.apellido, c.dni
            FROM pedidos_de_reparacion pr
            JOIN clientes c ON pr.id_clientes = c.id_clientes
            WHERE pr.numero_orden = ? AND c.dni = ? 
            " . ($customer_name ? "AND c.nombre = ?" : "") . " 
            " . ($customer_lastname ? "AND c.apellido = ?" : "")
        );
        if ($stmt) {
            if ($customer_name && $customer_lastname) {
                $stmt->bind_param('ssss', $order_number, $dni, $customer_name, $customer_lastname);
            } elseif ($customer_name) {
                $stmt->bind_param('sss', $order_number, $dni, $customer_name);
            } elseif ($customer_lastname) {
                $stmt->bind_param('sss', $order_number, $dni, $customer_lastname);
            } else {
                $stmt->bind_param('ss', $order_number, $dni);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();

            // Definir el mensaje y la clase CSS para el estado
            $status_message = '';
            $status_class = '';

            if ($result->num_rows > 0) {
                $order_info = $result->fetch_assoc();
                $estado = htmlspecialchars($order_info['estado_reparacion']);

                // Definir clase CSS según el estado
                switch ($estado) {
                    case 'Pendiente':
                        $status_class = 'status-pendiente';
                        break;
                    case 'Completado':
                        $status_class = 'status-completado';
                        break;
                    case 'Cancelado':
                        $status_class = 'status-cancelado';
                        break;
                    case 'En proceso':
                        $status_class = 'status-en-proceso';
                        break;
                    case 'Entregado':
                        $status_class = 'status-entregado';
                        break;
                    default:
                        $status_class = 'status-default';
                        break;
                }
                $status_message = "<strong>Estado:</strong> <span class='{$status_class}'>{$estado}</span>";
                
                echo "<div class='alert alert-info'>";
                echo "<p><strong>Número de Orden:</strong> " . htmlspecialchars($order_info['numero_orden']) . "</p>";
                echo "<p><strong>Nombre del Cliente:</strong> " . htmlspecialchars($order_info['nombre']) . " " . htmlspecialchars($order_info['apellido']) . "</p>";
                echo "<p><strong>DNI del Cliente:</strong> " . htmlspecialchars($order_info['dni']) . "</p>";
                echo "<p>{$status_message}</p>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning'>No se encontró ninguna información con los datos proporcionados.</div>";
            }
            $stmt->close(); // Cerrar el statement si se ha creado
        } else {
            echo "<div class='alert alert-danger'>Error al preparar la consulta.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Por favor, complete los campos obligatorios: Número de Orden y DNI.</div>";
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
        .status-completado {
            font-size: 2em; /* Tamaño de fuente más grande */
            color: #28a745;
            font-weight: bold;
        }
        .status-pendiente {
            font-size: 2em; /* Tamaño de fuente más grande */
            color: #ffc107;
            font-weight: bold;
        }
        .status-cancelado {
            font-size: 2em; /* Tamaño de fuente más grande */
            color: #dc3545;
            font-weight: bold;
        }
        .status-en-proceso {
            font-size: 2em; /* Tamaño de fuente más grande */
            color: #17a2b8;
            font-weight: bold;
        }
        .status-entregado {
            font-size: 2em; /* Tamaño de fuente más grande */
            color: #007bff;
            font-weight: bold;
        }
        .status-default {
            font-size: 2em; /* Tamaño de fuente más grande */
            color: #6c757d;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Consulta de Estado</h2>
        
        <!-- Formulario para ingresar los datos -->
        <form action="check_status.php" method="POST" class="mb-4">
            <div class="form-group">
                <label for="order_number">Número de Orden:</label>
                <input type="text" id="order_number" name="order_number" class="form-control" placeholder="Ingrese el número de orden" value="<?php echo htmlspecialchars($order_number); ?>" required>
            </div>
            <div class="form-group">
                <label for="dni">Número de DNI del Cliente:</label>
                <input type="text" id="dni" name="dni" class="form-control" placeholder="Ingrese el DNI del cliente" value="<?php echo htmlspecialchars($dni); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Consultar</button>
        </form>
        
        <div class="mt-4">
            <a href="../cliente/cliente.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
