<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Mi Empresa'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* Estilo para el contenedor del título */
        #title-container {
            text-align: center;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 180px;
            background: linear-gradient(135deg, #2c3e58, #3498db);
            color: gainsboro;
            padding: 50px;
            border-bottom: 5px solid #0056b3;
            position: fixed; /* Fija el contenedor en la parte superior */
            top: 0; /* Ubicación en la parte superior */
            left: 0;
            right: 0;
            z-index: 1000; /* Asegura que esté por encima de otros elementos */
        }
        body {
            padding-top: 180px; /* Da espacio para el contenedor fijo */
        }
        #title-button {
            font-size: 2rem;
            color: #fff;
            background-color: green;
            border: none;
            cursor: pointer;
            text-decoration: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        #title-button:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
        #title-logo {
            width: 100px;
            height: 100px;
            margin-right: 15px;
        }
        @media (max-width: 768px) {
            #title-container {
                height: auto;
                margin: 0.5rem 0;
                padding: 10px;
                flex-direction: column;
            }
            #title-button {
                font-size: 1.5rem;
                padding: 0.5rem 1rem;
            }
            #title-logo {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div id="title-container" class="container-fluid">
        <img id="title-logo" src="../../pdf/logo.png" alt="Logo de la empresa">
        <button id="title-button" onclick="window.location.reload(); return false;">
            Marquez Comunicaciones
        </button>
    </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
