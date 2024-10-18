<?php
require_once '../base_datos/db.php'; // Asegúrate de que db.php define y exporta $conn

function obtenerIconosPanel() {
    global $conn;
    $query = "SELECT descripcion_icono, estado FROM panel_administrativo";
    
    $result = $conn->query($query);
    $iconos = [];
    
    // Definir las rutas de los iconos
    $rutas = [
        'Compra' => ['icono' => 'fa-shopping-cart', 'ruta' => '../facturacion/venta/venta.php'],
        'Venta' => ['icono' => 'fa-shopping-basket', 'ruta' => '../facturacion/cargaFactura.php'],
        'Reparación' => ['icono' => 'fa-tools', 'ruta' => '../../administrativo/pedidos_de_reparacion/index.php'],
        'Reparación + Venta' => ['icono' => 'fa-exchange-alt', 'ruta' => '../facturacion/factura.html'],
        'Comprobantes' => ['icono' => 'fa-file-alt', 'ruta' => '../pdf/facturaB.php'],
    ];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Almacenar cada icono en el array
            $descripcion = $row['descripcion_icono'];
            if (array_key_exists($descripcion, $rutas)) {
                $iconos[$descripcion] = [
                    'estado' => $row['estado'], // Esta línea se mantiene por si necesitas usarla más adelante
                    'icono' => $rutas[$descripcion]['icono'],
                    'ruta' => $rutas[$descripcion]['ruta']
                ];
            }
        }
    } else {
        echo "<script>alert('Error: No se encontraron iconos en la base de datos.');</script>";
    }

    return $iconos;
}

// Usar la función para obtener los iconos
$iconos = obtenerIconosPanel();
?>
