<?php
// Iniciar sesión si no se ha iniciado ya
session_start();

// Incluir el archivo de conexión
require_once '../../sistema_marquez/base_datos/db.php';

// Consultar la tabla panel_administrativo
$query = "SELECT id_administrativo, descripcion_icono, estado FROM panel_administrativo";
$result = $conn->query($query);
$iconos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $iconos[] = $row; // Almacenar cada fila en el array
    }
} else {
    echo "<script>alert('Error: No se encontraron iconos en la base de datos.');</script>";
}

$conn->close(); // Cerrar la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Panel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin-top: 20px;
        }
        .card {
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
            transition: transform 0.2s; /* Añadir efecto de transformación */
        }
        .card:hover {
            transform: scale(1.05); /* Efecto de hover */
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn-toggle {
            margin-top: 10px;
        }
        /* Estilos para estados */
        .estado-habilitado {
            background-color: #d4edda; /* Verde claro */
            color: #155724; /* Texto verde */
        }
        .estado-deshabilitado {
            background-color: #f8d7da; /* Rojo claro */
            color: #721c24; /* Texto rojo */
        }
        .checkbox-label {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h4 class="mb-4">Configuración de Permisos Administrativos</h4>
    
    <form method="POST" action="actualizar_estado.php">
        <div class="row g-4">
            <?php foreach ($iconos as $icono): ?>
                <div class="col-md-3 mb-4">
                    <div class="card <?php echo $icono['estado'] ? 'estado-habilitado' : 'estado-deshabilitado'; ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars(ucfirst($icono['descripcion_icono'])); ?></h5>
                            <p>Estado: <?php echo $icono['estado'] ? 'Habilitado' : 'Deshabilitado'; ?></p>
                            <label class="checkbox-label">
                                <input type="checkbox" name="iconos[]" value="<?php echo htmlspecialchars($icono['descripcion_icono']); ?>">
                                Seleccionar
                            </label>
                            <input type="hidden" name="estados[<?php echo htmlspecialchars($icono['descripcion_icono']); ?>]" value="<?php echo $icono['estado'] ? 0 : 1; ?>"> <!-- Estado invertido -->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Estado</button>
        <a href="../base_datos/gestionar_permisos.php" class="btn btn-secondary">Volver Atrás</a> <!-- Botón Volver Atrás -->
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
