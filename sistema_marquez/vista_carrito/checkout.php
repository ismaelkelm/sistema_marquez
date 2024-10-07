<?php
session_start();

if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "El carrito está vacío.";
    exit;
}

// Aquí iría el código para procesar el pago

echo "<h2>Gracias por su compra</h2>";
$_SESSION['cart'] = array(); // Vaciar el carrito
?>
