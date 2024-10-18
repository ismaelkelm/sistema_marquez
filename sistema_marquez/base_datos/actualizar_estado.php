<?php
// Incluir el archivo de conexión
require_once '../../sistema_marquez/base_datos/db.php';

// Verificar que se han enviado datos
if (!isset($_POST['iconos']) || !isset($_POST['estados'])) {
    die('Datos incompletos.');
}

$iconos_seleccionados = $_POST['iconos'];
$estados = $_POST['estados'];

$actualizaciones = 0; // Contador de actualizaciones

// Preparar la consulta
$query = "UPDATE panel_administrativo SET estado = ? WHERE descripcion_icono = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

// Recorrer cada icono seleccionado
foreach ($iconos_seleccionados as $icono) {
    $nuevo_estado = $estados[$icono]; // Obtener el nuevo estado
    
    // Actualizar estado
    $stmt->bind_param("is", $nuevo_estado, $icono);
    $stmt->execute();

    // Incrementar contador de actualizaciones si se afectó alguna fila
    if ($stmt->affected_rows > 0) {
        $actualizaciones++;
    }
}

$stmt->close();
$conn->close();

if ($actualizaciones > 0) {
    echo "<script>
            alert('Estados actualizados correctamente.');
            setTimeout(function() {
                window.location.href = 'gestionar_panel.php'; // Cambia esto por la URL de tu página de gestión
            }, 1000); // 1000 milisegundos = 1 segundo
          </script>";
} else {
    echo "<script>
            alert('No se actualizó ningún registro.');
            setTimeout(function() {
                window.location.href = 'gestionar_permisos.php'; // Cambia esto por la URL de tu página de gestión
            }, 1000); // 1000 milisegundos = 1 segundo
          </script>";
}
?>
