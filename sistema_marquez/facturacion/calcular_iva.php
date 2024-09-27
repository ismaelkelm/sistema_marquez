<?php
// Conexión a la base de datos
require_once '../base_datos/db.php'; // Asegúrate de que la ruta sea correcta

// Inicializa variables
$response = [
    'error' => true,
    'message' => '',
    'total_con_iva' => 0,
    'iva' => 0
];

// Verifica si se han enviado datos a través de AJAX
if (isset($_POST['subtotal']) && isset($_POST['id_tipo_comprobante'])) {
    $subtotal = (float) $_POST['subtotal'];
    $id_tipo_comprobante = (int) $_POST['id_tipo_comprobante'];

    if ($subtotal > 0 && $id_tipo_comprobante > 0) {
        // Consulta para obtener el tipo de comprobante desde la base de datos
        $sql = "SELECT tipo_comprobante FROM tipo_comprobante WHERE id_tipo_comprobante = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $response['message'] = "Error en la preparación de la consulta: " . $conn->error;
            echo json_encode($response);
            exit();
        }

        $stmt->bind_param('i', $id_tipo_comprobante);

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            $response['message'] = "Error en la ejecución de la consulta: " . $stmt->error;
            echo json_encode($response);
            exit();
        }

        // Obtener el resultado
        $resultado = $stmt->get_result();
        $comprobante = $resultado->fetch_assoc();

        if ($comprobante) {
            if ($comprobante['tipo_comprobante'] === 'Factura A') {
                // Si es Factura A, calcular el IVA
                $iva = $subtotal * 0.21;
                $total = $subtotal + $iva;

                $response['error'] = false;
                $response['iva'] = number_format($iva, 2);
                $response['total'] = number_format($total, 2);
            } else {
                $response['message'] = "El tipo de comprobante no requiere IVA.";
                $response['total'] = number_format($subtotal, 2);
            }
        } else {
            $response['message'] = "No se encontró el tipo de comprobante con el ID especificado.";
        }
    } else {
        $response['message'] = "Total o tipo de comprobante inválido.";
    }
} else {
    $response['message'] = "Datos incompletos.";
}

echo json_encode($response);
?>