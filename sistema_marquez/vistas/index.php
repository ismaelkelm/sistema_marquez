<?php
// Incluye archivos necesarios
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Sitio Web</title>
    <!-- Enlazar el archivo CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Tu archivo CSS personalizado -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>
    
    <div class="container my-4">
        <h2>Bienvenido a nuestro sitio web</h2>
        <p>Contenido principal aquí...</p>
        
        <!-- Formulario para consulta de estado -->
        <div class="my-4">
            <h2>Consulta de Estado de Reparación</h2>
            <form id="status-form" method="post" action="index.php">
                <div class="form-group">
                    <label for="order-number">Número de Orden:</label>
                    <input type="text" class="form-control" id="order-number" name="order-number" placeholder="Ingrese su número de orden" required>
                </div>
                <button type="submit" class="btn btn-primary">Consultar Estado</button>
            </form>
            <div id="status-result" class="mt-4">
                <?php
                // Lógica para manejar la consulta de estado
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $orderNumber = $_POST['order-number'];
                    // Obtener el estado
                    $status = getOrderStatus($orderNumber);
                    echo "<div class='status {$status['class']}'>{$status['text']}</div>";
                }
                ?>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>

    <!-- Enlazar los archivos JS de Bootstrap y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Enlazar el archivo JS personalizado -->
    <script src="js/script.js"></script>
</body>
</html>
