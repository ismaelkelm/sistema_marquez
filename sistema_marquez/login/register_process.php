<?php
session_start();
require_once '../base_datos/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura y limpia los datos del formulario
    $nombre = trim($_POST["nombre"]);
    $dni = trim($_POST["dni"]);
    $contraseña = trim($_POST["contraseña"]);
    $correo = trim($_POST["correo"]);
    $id_roles = isset($_POST["id_roles"]) ? trim($_POST["id_roles"]) : '';

    // Verifica si los campos están vacíos
    if (empty($nombre) || empty($dni) || empty($contraseña) || empty($correo) || empty($id_roles)) {
        echo "Por favor, complete todos los campos.";
        exit;
    }

    // Valida el correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "Correo electrónico no válido.";
        exit;
    }

    // Verifica si el rol existe
    $sql_role = "SELECT id_roles FROM roles WHERE id_roles = ?";
    if ($stmt_role = $conn->prepare($sql_role)) {
        $stmt_role->bind_param("i", $id_roles);
        $stmt_role->execute();
        $stmt_role->store_result();

        if ($stmt_role->num_rows > 0) {
            // Verifica si el usuario ya existe
            $sql_user = "SELECT nombre FROM usuario WHERE nombre = ?";
            if ($stmt_user = $conn->prepare($sql_user)) {
                $stmt_user->bind_param("s", $nombre);
                $stmt_user->execute();
                $stmt_user->store_result();

                if ($stmt_user->num_rows > 0) {
                    echo "El nombre de usuario ya existe.";
                } else {
                    // Inserta el nuevo usuario (con hash para la contraseña)
                    $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);
                    $sql_insert = "INSERT INTO usuario (nombre, dni, contraseña, id_roles, correo_electronico) VALUES (?, ?, ?, ?, ?)";
                    if ($stmt_insert = $conn->prepare($sql_insert)) {
                        $stmt_insert->bind_param("sssis", $nombre, $dni, $hashed_password, $id_roles, $correo);
                        if ($stmt_insert->execute()) {
                            // Mostrar mensaje de bienvenida y redirigir
                            echo "
                            <html lang='es'>
                            <head>
                                <meta charset='UTF-8'>
                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                <title>Registro exitoso</title>
                                <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
                                <script>
                                    setTimeout(function() {
                                        window.location.href = '../login/login.php';
                                    }, 2000); // Redirige después de 2 segundos
                                </script>
                            </head>
                            <body>
                                <div class='container mt-5'>
                                    <div class='alert alert-success'>
                                        <strong>Registro exitoso!</strong> Bienvenido, $nombre. Serás redirigido a la página de inicio de sesión en 2 segundos.
                                    </div>
                                </div>
                            </body>
                            </html>";
                            exit;
                        } else {
                            echo "Error al registrar el usuario: " . $stmt_insert->error;
                        }
                        $stmt_insert->close();
                    } else {
                        echo "Error en la preparación de la consulta de inserción.";
                    }
                }
                $stmt_user->close();
            } else {
                echo "Error en la preparación de la consulta de usuario: " . $conn->error;
            }
        } else {
            echo "El rol seleccionado no está registrado.";
        }
        $stmt_role->close();
    } else {
        echo "Error en la preparación de la consulta de rol: " . $conn->error;
    }
    $conn->close();
} else {
    header("Location: register.php");
    exit;
}
?>
