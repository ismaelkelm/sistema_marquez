<?php
// Iniciar sesión si no se ha iniciado ya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
require_once '../base_datos/db.php'; // Asegúrate de que este archivo defina y exporte $conn

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

// Consultar el nombre del rol directamente desde la base de datos
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
if ($role_name !== 'Administrador') {
    header("Location: ../login/login.php");
    exit;
}

// Incluir los archivos comunes
$pageTitle = "Panel de Control - Administrador"; // Establecer el título específico para esta página
include('../includes/header.php'); // Asegúrate de que header.php no incluya nav.php nuevamente
include('../base_datos/icons.php'); // Incluir los iconos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../administrador/buscar/styles.css"> 
    <style>
        .card-icon {
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            background-color: #f9f9f9;
        }
        .card-icon:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-icon i {
            color: #007bff;
            font-size: 2rem; /* Ajusta el tamaño del icono aquí */
            transition: color 0.3s ease;
        }
        .card-icon:hover i {
            color: #dc3545; /* Cambia el color al pasar el ratón */
        }
        .card-icon .card-body {
            padding: 1.5rem;
        }
        .card-title {
            margin-top: 1rem;
        }
        .suggestion-item {
            cursor: pointer;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .suggestion-item:hover {
            background-color: #f1f1f1;
        }
        .table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include('../includes/nav.php'); ?>
    
    <div class="container">
        <form onsubmit="event.preventDefault();">
            <div class="form-group position-relative">
                <label for="descripcionPermiso">Descripción del Permiso:</label>
                <input type="text" id="descripcionPermiso" class="form-control" oninput="fetchSuggestions()" autocomplete="off">
                <div id="suggestions" class="suggestions"></div>
            </div>
        </form>
        <div id="result" class="table-container"></div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function fetchSuggestions() {
            const descripcion = document.getElementById('descripcionPermiso').value.trim();
            if (descripcion.length === 0) {
                document.getElementById('suggestions').innerHTML = '';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../administrador/buscar/get_permisos.php?descripcion=' + encodeURIComponent(descripcion), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Response from fetchSuggestions:', response); // Agregar log de respuesta
                        const suggestions = document.getElementById('suggestions');
                        suggestions.innerHTML = '';
                        if (response.success === false) {
                            suggestions.innerHTML = '<p class="text-center p-2">No se encontraron permisos.</p>';
                        } else {
                            response.forEach(function(permiso) {
                                const option = document.createElement('div');
                                option.className = 'suggestion-item';
                                option.textContent = permiso.descripcion;
                                option.dataset.id = permiso.id_permisos;
                                option.addEventListener('click', function() {
                                    document.getElementById('descripcionPermiso').value = this.textContent;
                                    suggestions.innerHTML = '';
                                    fetchPermissions(this.dataset.id);
                                });
                                suggestions.appendChild(option);
                            });
                        }
                    } catch (e) {
                        console.error('Error al procesar la respuesta JSON:', e);
                        alert('Error al procesar la respuesta del servidor.');
                    }
                } else {
                    console.error('Error al obtener los permisos:', xhr.statusText);
                    alert('Error al obtener los permisos: ' + xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Error en la solicitud de búsqueda.');
                alert('Error en la solicitud de búsqueda.');
            };
            xhr.send();
        }

        function fetchPermissions(idPermiso) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../administrador/buscar/get_permissions_by_id.php?id=' + idPermiso, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Response from fetchPermissions:', response);
                        const resultTable = document.getElementById('result');
                        resultTable.innerHTML = '';
                        if (Array.isArray(response) && response.length > 0) {
                            let tableHTML = '<table class="table table-bordered table-striped">' +
                                '<thead>' +
                                '<tr>' +
                                '<th>Seleccionar</th>' +
                                '<th>Rol</th>' +
                                '<th>ID Permiso</th>' +
                                '<th>Descripción</th>' +
                                '<th>Estado</th>' +
                                '<th>Acción</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>';
                            response.forEach(function(item) {
                                tableHTML += '<tr>' +
                                    '<td><input type="checkbox" class="role-checkbox" data-id="' + item.id_roles + '" data-perm="' + item.id_permisos + '"></td>' +
                                    '<td>' + item.rol_descripcion + '</td>' +
                                    '<td>' + item.id_permisos + '</td>' +
                                    '<td>' + item.permiso_descripcion + '</td>' +
                                    '<td>' + (item.estado == 1 ? 'Activo' : 'Inactivo') + '</td>' +
                                    '<td>' +
                                    '<button class="btn ' + (item.estado == 1 ? 'btn-danger' : 'btn-success') + '" ' +
                                    'onclick="toggleRoleState(' + item.id_permisos + ', ' + item.id_roles + ', ' + (item.estado == 1 ? '0' : '1') + ')">' +
                                    (item.estado == 1 ? 'Desactivar' : 'Activar') +
                                    '</button>' +
                                    '</td>' +
                                    '</tr>';
                            });
                            tableHTML += '</tbody></table>';
                            resultTable.innerHTML = tableHTML;
                        } else {
                            resultTable.innerHTML = '<p class="text-center p-2">No se encontraron roles para este permiso.</p>';
                        }
                    } catch (e) {
                        console.error('Error al procesar la respuesta JSON:', e);
                        alert('Error al procesar la respuesta del servidor esooo.');
                    }
                } else {
                    console.error('Error al obtener los permisos por ID:', xhr.statusText);
                    alert('Error al obtener los permisos por ID: ' + xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Error en la solicitud de permisos.');
                alert('Error en la solicitud de permisos.');
            };
            xhr.send();
        }

        function toggleRoleState(idPermiso, rolId, estado) {
            console.log('idPermiso:', idPermiso, 'rolId:', rolId, 'estado:', estado);
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../administrador/buscar/update_estado.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Response from toggleRoleState:', response);
                        if (response.success) {
                            alert('Estado actualizado con éxito.');
                            fetchPermissions(idPermiso); // Actualiza la tabla después de cambiar el estado
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Error al procesar la respuesta del servidor wachooo:', e);
                        alert('Error al procesar la respuesta del servidor vvvvvvvvvv.');
                    }
                } else {
                    console.error('Error al actualizar el estado:', xhr.statusText);
                    alert('Error al actualizar el estado: ' + xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Error en la solicitud de actualización.');
                alert('Error en la solicitud de actualización.');
            };
            xhr.send('id_permiso=' + encodeURIComponent(idPermiso) + '&rol_id=' + encodeURIComponent(rolId) + '&estado=' + encodeURIComponent(estado));
        }
    </script> 

    <div class="container my-4">
        <h4 class="mb-4">Panel de Control - Administrador</h4>
        <div class="row">
            <?php foreach ($iconos_visibles as $tabla => $icono): ?>
                <div class="col-md-3 mb-4">
                    <div class="card card-icon text-center">
                        <div class="card-body">
                            <a href="<?php echo htmlspecialchars($icono['ruta']); ?>">
                                <i class="fas <?php echo htmlspecialchars($icono['icono']); ?>"></i>
                                <h5 class="card-title mt-3"><?php echo htmlspecialchars(ucfirst($tabla)); ?></h5>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div> 

    <?php include('../includes/footer.php'); ?>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
