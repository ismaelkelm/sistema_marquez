<?php
// Iniciar sesión si no se ha iniciado ya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
require_once '../base_datos/db.php'; // Asegúrate de que este archivo define y exporta $conn

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Supongamos que el ID del usuario está almacenado en $_SESSION['user_id']
$user_id = $_SESSION['user_id'];

// Consultar el id_roles del usuario
$query = "SELECT id_roles FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error en la consulta: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Error: Usuario no encontrado.");
}

$id_roles = $row['id_roles'];

// Consultar el nombre del rol
$query = "SELECT nombre FROM roles WHERE id_roles = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error en la consulta: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $id_roles);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Error: Rol no encontrado.");
}

$role_name = $row['nombre'];

// Verificar si el usuario tiene el rol 'Administrativo'
if ($role_name !== 'Administrativo') {
    header("Location: ../login/login.php");
    exit;
}

// Incluir los archivos comunes
$pageTitle = "Panel de Control - Administrativo"; // Establecer el título específico para esta página
include('../includes/header.php'); // Asegúrate de que header.php no incluya nav.php nuevamente
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: grey; /* Fondo neutro */
            font-family: 'Arial', sans-serif; /* Fuente legible y moderna */
        }
        .card-icon {
            border: 1px solid red;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            background-color: black;
        }
        .card-icon:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card-icon i {
            color: yellow; /* Cambiar a color amarillo */
            font-size: 2rem;
            transition: color 0.3s ease;
        }
        .card-icon:hover i {
            color: #dc3545; /* Cambia de color al pasar el ratón */
        }
        .card-icon .card-body {
            padding: 1.5rem;
        }
        .card-title {
            margin-top: 1rem;
        }
        /* Estilo adicional para la disposición horizontal */
        .horizontal-row {
            display: flex;
            flex-wrap: wrap; /* Para que los elementos se envuelvan si no hay espacio */
            justify-content: space-between; /* Espaciado horizontal */
        }
    </style>
    <script>
        // Función para alternar la visibilidad de un panel
        function toggleVisibility(panelId) {
            var panel = document.getElementById(panelId);
            if (panel.style.display === "none" || panel.style.display === "") {
                panel.style.display = "block"; // Muestra el panel
            } else {
                panel.style.display = "none"; // Oculta el panel
            }
        }

        // Configura el estado inicial de los paneles
        document.addEventListener('DOMContentLoaded', (event) => {
            var operationPanel = document.getElementById('operationsPanel');
            var controlPanel = document.getElementById('controlPanel');
            operationPanel.style.display = 'block'; // Asegura que el panel de operaciones esté visible al cargar la página
            controlPanel.style.display = 'block'; // Asegura que el panel de control esté visible al cargar la página
        });
    </script>
</head>
<body>
    <?php include('../includes/nav.php'); ?>

    <div class="container my-4">
        <!-- Panel de Operaciones -->
        <h4 class="mb-4">Panel de Operaciones - Administrativo</h4>
        
        <!-- Botón para alternar visibilidad del Panel de Operaciones -->
        <button class="btn btn-primary mb-3" onclick="toggleVisibility('operationsPanel')">Toggle Panel de Operaciones</button>

        <div id="operationsPanel" class="horizontal-row">
            <?php include '../base_datos/icons_administrativo.php'; // Incluye los iconos administrativos ?>
            <?php if (isset($iconos) && is_array($iconos) && count($iconos) > 0): ?>
                <div class="row g-4">
                    <?php
                    $iconos_a_mostrar = [
                        'Compra',
                        'Venta',
                        'Reparación',
                        'Reparación + Venta',
                        'Comprobantes'
                    ];
                    
                    // Filtrar y mostrar solo los iconos seleccionados
                    foreach ($iconos as $descripcion => $info): 
                        if (in_array($descripcion, $iconos_a_mostrar)): // Verificar si el icono debe ser mostrado
                    ?>
                            <div class="col-md-3 mb-4">
                                <div class="card card-icon text-center">
                                    <div class="card-body">
                                        <a href="<?php echo htmlspecialchars($info['ruta']); ?>">
                                            <i class="fas <?php echo htmlspecialchars($info['icono']); ?> fa-2x"></i>
                                            <h5 class="card-title mt-3"><?php echo htmlspecialchars($descripcion); ?></h5> <!-- Mostrar la descripción -->
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Error: No se encontraron iconos visibles.</p>
            <?php endif; ?>
        </div>

        <!-- Panel de Control -->
        <div class="container my-4">
            <h4 class="mb-4">Panel de Control - Administrativo</h4>

            <!-- Botón para alternar visibilidad del Panel de Control -->
            <button class="btn btn-primary mb-3" onclick="toggleVisibility('controlPanel')">Toggle Panel de Control</button>

            <div id="controlPanel" class="horizontal-row">
                <?php include '../base_datos/icons.php'; // Incluye los iconos administrativos ?>
                <div class="row g-4">
                    <?php if (isset($iconos_visibles) && is_array($iconos_visibles) && count($iconos_visibles) > 0): ?>
                        <?php foreach ($iconos_visibles as $tabla => $icono): ?>
                            <div class="col-md-3 mb-4">
                                <div class="card card-icon text-center">
                                    <div class="card-body">
                                        <a href="<?php echo htmlspecialchars($icono['ruta']); ?>">
                                            <i class="fas <?php echo htmlspecialchars($icono['icono']); ?> fa-2x"></i>
                                            <h5 class="card-title mt-3"><?php echo htmlspecialchars(ucfirst($tabla)); ?></h5> <!-- Mostrar la descripción -->
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Error: No se encontraron iconos visibles.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php include('../includes/footer.php'); ?>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
