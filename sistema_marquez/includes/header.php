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
            display: flex;
            align-items: center;
            justify-content: space-between; /* Espacio entre logo y título */
            height: 130px; /* Altura del contenedor */
            background: linear-gradient(135deg, #555, #00aaff);

            color: gainsboro;
            padding: 10px 20px; /* Mayor padding a los lados */
            border-bottom: 5px solid #0056b3;
            position: fixed; /* Fija el contenedor en la parte superior */
            top: 0; /* Ubicación en la parte superior */
            left: 0;
            right: 0;
            z-index: 1000; /* Asegura que esté por encima de otros elementos */
            transition: all 0.3s ease; /* Transición suave */
        }

        body {
            padding-top: 130px; /* Espacio para el contenedor fijo */
            transition: padding-top 0.3s ease; /* Transición suave del padding */
        }

        #title-button {
            font-size: 2rem;
            color: #fff;
            background-color: transparent; /* Fondo transparente */
            border: none;
            cursor: pointer;
            text-decoration: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        #title-button:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Cambio de fondo al pasar el mouse */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        #title-logo {
            width: 100px;
            height: 100px;
            margin-right: 20px; /* Espacio entre el logo y el botón */
            transition: transform 0.3s; /* Transición suave para el logo */
        }

        @media (max-width: 768px) {
            #title-container {
                flex-direction: column; /* Cambio a dirección vertical en pantallas pequeñas */
                height: auto; /* Altura automática */
                padding: 10px; /* Reducir padding */
            }

            #title-button {
                font-size: 1.5rem; /* Reducir tamaño del texto */
                padding: 0.5rem 1rem; /* Ajustar padding */
            }

            #title-logo {
                width: 60px; /* Cambiar tamaño del logo */
                height: 60px;
                margin: 0 0 10px 0; /* Espacio debajo del logo */
                transition: transform 0.3s; /* Transición suave para el logo */
            }
        }
    </style>
</head>
<body>
    <div id="title-container" class="container-fluid">
        <img id="title-logo" src="../../pdf/logo.png" alt="Logo de la empresa">
        <button id="title-button" onclick="moveTitle()">
            Marquez Comunicaciones
        </button>
    </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Posiciones para el botón
        const positions = [
            { justifyContent: 'space-between', order: '0' }, // Original
            { justifyContent: 'flex-start', order: '0' }, // Izquierda
            { justifyContent: 'center', order: '0' }, // Centro
            { justifyContent: 'flex-end', order: '0' } // Derecha
        ];

        let currentPosition = 0;

        // Función para mover el título a diferentes posiciones
        function moveTitle() {
            const titleContainer = document.getElementById('title-container');
            currentPosition = (currentPosition + 1) % positions.length; // Cambiar posición
            titleContainer.style.justifyContent = positions[currentPosition].justifyContent;
        }
    </script>
</body>
</html>
