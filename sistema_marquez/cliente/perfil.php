<?php
include_once '../base_datos/db.php'; // Incluir la conexión a la base de datos

// Suponiendo que $id_cliente se obtiene de la URL
$id_cliente = isset($_GET['id_clientes']) ? intval($_GET['id_clientes']) : 2;

// Depuración: verificar el valor recibido
var_dump($id_cliente); // Muestra el valor de $id_cliente

if ($id_cliente > 0) {
    // Preparar la consulta con JOIN a la tabla usuario
    $stmt = $conn->prepare("
        SELECT 
            c.id_clientes, 
            c.nombre AS cliente_nombre, 
            c.apellido, 
            c.telefono, 
            c.correo_electronico, 
            c.direccion, 
            c.dni, 
            u.id_usuario,
            u.nombre AS usuario_nombre
        FROM 
            clientes c
        JOIN 
            usuario u ON c.id_clientes = u.id_usuario
        WHERE 
            c.id_clientes = ?
    ");
    $stmt->bind_param('i', $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el cliente
    if ($result->num_rows > 0) {
        $cliente_info = $result->fetch_assoc();
        // Mostrar la información del cliente
        echo "<h2>Información del Cliente</h2>";
        echo "<p>Nombre del Cliente: " . htmlspecialchars($cliente_info['cliente_nombre']) . "</p>";
        echo "<p>Apellido: " . htmlspecialchars($cliente_info['apellido']) . "</p>";
        echo "<p>Teléfono: " . htmlspecialchars($cliente_info['telefono']) . "</p>";
        echo "<p>Correo Electrónico: " . htmlspecialchars($cliente_info['correo_electronico']) . "</p>";
        echo "<p>Dirección: " . htmlspecialchars($cliente_info['direccion']) . "</p>";
        echo "<p>DNI: " . htmlspecialchars($cliente_info['dni']) . "</p>";
        echo "<p>ID Usuario: " . htmlspecialchars($cliente_info['id_usuario']) . "</p>"; // ID de usuario
        echo "<p>Nombre de Usuario: " . htmlspecialchars($cliente_info['usuario_nombre']) . "</p>"; // Nombre del usuario
    } else {
        echo "<div class='alert alert-danger'>No se encontró información para este cliente.</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-warning'>ID de cliente no válido: " . htmlspecialchars($id_cliente) . "</div>";
}

mysqli_close($conn);
?>
