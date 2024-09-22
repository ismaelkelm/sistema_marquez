<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marquez Comunicaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        nav {
            position: relative;
            background-color: black;
            color: #fff;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-header {
            color: white;
            font-size: 49px;
            text-align: center;
            flex: 1;
            animation: colorChange 3s infinite;
        }
        @keyframes colorChange {
            0% { color: blue; }
            50% { color: red; }
            100% { color: green; }
            100% { color: orange; }
            100% { color: yellow; }
            100% { color: greenyellow; }
        }
        .menu-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            margin: 10;
            display: flex;
            align-items: right;
            justify-content: right;
        }
        .menu-btn:hover {
            background-color: red;
        }
        .nav-menu {
            display: none;
            list-style-type: none;
            padding: 10;
            margin: 10;
            position: absolute;
            top: 10px;
            right: 0;
            background-color: transparent;
            z-index: 1;
        }
        .nav-menu li {
            margin: 0;
        }
        .nav-menu li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        .nav-menu li a:hover {
            background-color: transparent;
            color: yellow;
        }
        .carousel {
            position: relative;
            width: 100%;
            height: 300px; /* Ajusta la altura según el tamaño deseado */
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .carousel img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Esto asegura que las imágenes cubran el área del contenedor */
            display: block;
        }

        .carousel .nav-button {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            background-color: rgba(0,0,0,0.5);
            border: none;
            border-radius: 50%;
            font-size: 24px;
            text-align: center;
        }

        .carousel .prev {
            left: 10px;
            border-radius: 50%;
        }

        .carousel .next {
            right: 10px;
            border-radius: 50%;
        }
        .container {
            flex: 1;
            padding: 0;
            margin: 0;
        }
        .services {
            position: relative;
            text-align: left;
            background: url('presentacion/3.png') no-repeat center center;
            background-size: cover;
            padding: 100px 0;
            color: white;
            height: 90vh; /* Ajusta la altura para asegurar que la imagen de fondo se vea completa */
        }
        .services .service {
            background-color: orange;
            padding: 5px;
            border-radius: 10px;
            box-shadow: 0 0 1px rgba(0,0,0,0.1);
            margin: 15px 20px;
            width: 80%;
            max-width: 260px;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }
        .services .service:hover {
            background-color: blue;
            color: blueviolet;
        }
        .services .service h3 {
            margin: 0;
            color: inherit;
            font-size: 20px;
        }
        .upload-section {
            text-align: center;
            margin-top: 40px;
        }
        .upload-section input[type="file"] {
            margin: 20px 0;
        }
        footer {
            background-color: black;
            color: #fff;
            text-align: center;
            padding: 10px;
            width: 100%;
            margin-top: 0;
        }
        footer .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: left;
            align-items: center;
            gap: 10px;
        }
        footer .container p, footer .container a {
            margin: 0;
        }
        footer .qr-code {
            width: 50px;
            height: auto;
            transition: transform 0.1s;
        }
        footer .qr-code:hover {
            transform: scale(1.8);
        }
        .whatsapp-bubble {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            z-index: 1000;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .whatsapp-bubble img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        .whatsapp-bubble:hover {
            transform: scale(1.1);
        }
        @media (max-width: 768px) {
            .nav-menu {
                position: static;
                width: 100%;
            }
            .nav-menu li {
                border-top: 1px solid #fff;
            }
            .nav-menu li a {
                padding: 15px 10px;
                font-size: 16px;
            }
            .menu-btn {
                display: block;
            }
            .services {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .services .service {
                width: 100%;
                max-width: 300px;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-header">
            Marquez Comunicaciones
        </div>
        <button class="menu-btn" onclick="toggleMenu()">Menú</button>
        <ul class="nav-menu">
            <li><a href="login/login.php">Iniciar Sesion</a></li>
            <li><a href="base_datos/check_status.php">Consultar Estado de reparación</a></li>
        </ul>
    </nav>
        <div class="carousel">
        <img class="mySlides" src="presentacion/reparacion-computadoras.jpg" alt="Imagen 1">
        <img class="mySlides" src="presentacion/reparacion_de_celulares.jpg" alt="Imagen 2">
        <img class="mySlides" src="presentacion/reparacion_electronica.jpg" alt="Imagen 3">
        <img class="mySlides" src="presentacion/6.png" alt="Imagen 6">
        <img class="mySlides" src="presentacion/logo.png" alt="Imagen 6">

        <a class="nav-button prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="nav-button next" onclick="plusSlides(1)">&#10095;</a>
    </div>
    <div class="container">
        <div class="services">
            <div class="service">
                <h3>Pcs</h3>
            </div>
            <div class="service">
                <h3>Celulares</h3>
            </div>
            <div class="service">
                <h3>Instalación de Redes</h3>
            </div>
            <div class="service">
                <h3>Desarrollo de Software</h3>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Servicio Técnico. Todos los derechos reservados.</p>
            <p>Síguenos en <a href="https://www.instagram.com/tu_cuenta_de_instagram" target="_blank" class="text-white">Instagram</a></p>
            <img src="presentacion/insta.png" alt="Código QR" class="qr-code">
            <a href="mailto:emailDeltecnico@gmail.com" class="text-white">emailDeltecnico@gmail.com</a>
            <img src="presentacion/QR.png" alt="Código QR" class="qr-code">
        </div>
    </footer>
    <a href="https://wa.me/15551234567" target="_blank" class="whatsapp-bubble" id="draggable">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/WhatsApp_icon.png" alt="WhatsApp">
    </a>
    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            const slides = document.getElementsByClassName("mySlides");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) { slideIndex = 1 }
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 5000);
        }

        function plusSlides(n) {
            slideIndex += n;
            showSlides();
        }

        function toggleMenu() {
            const menu = document.querySelector('.nav-menu');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }
        function initMap() {
            // Coordenadas del lugar (ejemplo: Buenos Aires, Argentina)
            const location = { lat: -34.6037, lng: -58.3816 };

            // Crear un nuevo mapa
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: location
            });

            // Agregar un marcador en la ubicación
            const marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }
    </script>
</body>
</html>
