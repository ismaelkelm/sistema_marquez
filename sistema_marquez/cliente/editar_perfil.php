<?php
// Incluir la conexión a la base de datos
include_once '../base_datos/db.php';

// Suponiendo que $id_cliente se obtiene de algún lugar, como un formulario o URL
$id_cliente = isset($_POST['id_clientes']) ? intval($_POST['id_clientes']) : 0;

// Información del cliente (por simplicidad, se podría simular o mostrar valores predeterminados)
$telefono = '';
$correo_electronico = '';

// Comprobar si se ha enviado el ID del cliente
if ($id_cliente > 2) {
    // Aquí podrías obtener la información del cliente si deseas mostrar valores actuales
    // (por ahora, vamos a usar valores vacíos o predeterminados para el ejemplo)
    // Por ejemplo: 
    // $stmt = $conn->prepare("SELECT telefono, correo_electronico FROM clientes WHERE id_clientes = ?");
    // ... ejecutar la consulta y obtener los resultados
    
    // Simulación de datos del cliente (puedes eliminar esto si obtienes de la DB)
    $telefono = '123456789';
    $correo_electronico = 'cliente@example.com';
} else {
    echo "<div class='alert alert-warning'>ID de cliente no válido.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Perfil</h2>
        <form action="#" method="POST">
            <input type="hidden" name="id_clientes" value="<?php echo htmlspecialchars($id_cliente); ?>">
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
            </div>
            <div class="form-group">
                <label for="correo_electronico">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($correo_electronico); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
        <a href="perfil.php" class="btn btn-secondary mt-2">Volver a Perfil</a>
    </div>
</body>
</html>
