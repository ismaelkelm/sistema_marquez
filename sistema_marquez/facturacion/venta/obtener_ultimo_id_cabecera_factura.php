<?php
require_once '../../base_datos/db.php'; // Asegúrate de que esta ruta sea correcta

// Verificar conexión
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Consulta para obtener el último id_cabecera_factura
$query = "SELECT id_cabecera_factura FROM cabecera_factura ORDER BY id_cabecera_factura DESC LIMIT 1";

if ($stmt = mysqli_prepare($conn, $query)) {
    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);

    // Obtener el resultado
    $result = mysqli_stmt_get_result($stmt);

    // Manejo de resultados
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $ultimoId = $row['id_cabecera_factura'];

            // Si el id_cabecera_factura no viene de la URL, asignar el último ID obtenido
            $id_cabecera_factura = isset($_GET['id']) ? (int)$_GET['id'] : $ultimoId;

            echo "ID cabecera factura asignado: " . $id_cabecera_factura; // Mostrar el ID asignado
        } else {
            echo "No se encontró ningún registro de cabecera de factura.";
        }
    } else {
        echo "Error al obtener el resultado: " . mysqli_error($conn);
    }

    // Cerrar el statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error al preparar la consulta: " . mysqli_error($conn);
}

// Cerrar la conexión
mysqli_close($conn);
?>