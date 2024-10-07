<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marquez Comunicaciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* Estilo para el body */
        body {
            background-color: #d8d6d6; /* Fondo del body */
        }
    
        /* Estilo para el banner superior */
        .top-banner {
            background-color: rgb(255, 94, 0);
            color: rgb(245, 243, 243);
            text-align: center;
            padding: 10px 0;
        }
    
        /* Estilo del header con el logo y el buscador */
        .header {
            background-color: #458a8b;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    
        .header .logo {
            margin-left: 20px;
        }
    
        .header .search-bar {
            flex-grow: 1;
            margin: 0 20px;
        }
    
        .header .icons {
            margin-right: 20px;
        }
    
        .header .icons a {
            color: white;
            margin-left: 15px;
        }
    
        /* Estilo del menú */
        .menu {
            background-color: #458a8b;
            padding: 10px 0;
        }
    
        .menu a {
            color: rgb(235, 241, 234);
            margin: 0 15px;
        }
    
        .menu a:hover {
            text-decoration: underline;
        }
    
        /* Estilo del segundo nav */
        .navbar {
            margin-top: 20px;
            background-color: #458a8b;  /* Fondo del segundo nav */
            font-weight: bold; /* Negrita */
        }
    
        .navbar-nav .nav-item {
            position: relative; /* Necesario para el efecto hover */
        }
    
        .navbar-nav .nav-link {
            padding: 15px 20px; /* Espaciado alrededor de los enlaces */
            color: #458a8b; /* Color del texto */
            font-weight: bold; /* Negrita */
            transition: color 0.3s ease; /* Transición suave */
        }
    
        .navbar-nav .nav-link:hover {
            color: rgb(255, 94, 0); /* Color del texto al pasar el mouse */
            text-decoration: underline; /* Subrayado al pasar el mouse */
        }
    
        .navbar-nav .nav-link.active {
            color: rgb(255, 94, 0); /* Color activo */
            border-bottom: 2px solid rgb(255, 94, 0); /* Línea debajo del enlace activo */
        }
    
        /* Estilo del carrusel */
        .carousel-item img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }
    
        .carousel-caption h5 {
            font-size: 2rem;
            font-weight: bold;
        }
    
        .carousel-caption p {
            font-size: 1.2rem;
        }
    
        /* Estilo del botón flotante de WhatsApp */
        .whatsapp-bubble {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background-color: #25D366;
            border-radius: 50%;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    
        .whatsapp-bubble img {
            width: 40px;
            height: 40px;
        }
    
        .whatsapp-bubble:hover {
            background-color: #128C7E;
        }
    
        /* Footer */
        .footer {
            background-color: #f2f5f8;
            padding: 20px;
            text-align: center;
        }
    
        /* Estilo para los códigos QR */
        .qr-code {
            width: 100px; /* Tamaño del código QR */
            margin-top: 10px;
        }

        /* Estilos para los resultados de búsqueda */
        #search-results {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
        }

        .search-result-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }
    </style>
    
</head>
<body>

    <div class="top-banner">
        Enterate todas las novedades, seguinos en Instagram 
        <a href="https://www.instagram.com/tu_cuenta_de_instagram" target="_blank" style="color: white; text-decoration: underline;">Marquez Comunicaciones</a>
    </div>

    <header class="header">
        <div class="logo">
            <img src="../../presentacion/ios.png" alt="Logo de Marquez Comunicaciones" width="120">
        </div>
        <div class="search-bar d-flex align-items-center">
            <input type="text" id="search-input" class="form-control" placeholder="Buscar Accesorios, PC, Celulares, Electrónica..." aria-label="Buscar">
            
            <!-- Botones para buscar diferentes tipos -->
            <button id="search-button-clientes" class="btn" aria-label="Buscar Clientes" onclick="searchItems('clientes');">Buscar Clientes</button>
            <button id="search-button-dispositivos" class="btn" aria-label="Buscar Dispositivos" onclick="searchItems('dispositivos');">Buscar Dispositivos</button>
            <button id="search-button-accesorios" class="btn" aria-label="Buscar Accesorios" onclick="searchItems('accesorios');">Buscar Accesorios</button>
            
            <!-- Botón para recargar la página -->
            <button id="reload-button" class="btn" aria-label="Recargar página" onclick="location.reload();">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
        
        <div class="icons">
            <a href="login/login.php" class="icon-link" aria-label="Iniciar Sesión">Iniciar Sesión <i class="fas fa-sign-in-alt"></i></a>
            <a href="vista_carrito/cart.php" class="icon-link" aria-label="Ver Carrito"><i class="fas fa-shopping-cart"></i></a>
        </div>
    </header>
    
    <div class="container loading-container">
        <div id="loading-spinner" style="display: none;">Cargando...</div>
        <div id="result" aria-live="polite"></div> <!-- Añadir aria-live para accesibilidad -->
    </div>
    
    <script defer>
        function searchItems(type) {
            const query = document.getElementById('search-input').value;
    
            if (query.trim() !== "") {
                showLoadingSpinner();
                fetchResults(type, query);
            } else {
                alert("Por favor, ingresa un término de búsqueda.");
            }
        }
    
        function showLoadingSpinner() {
            document.querySelector('.loading-container').style.display = 'block'; 
            document.getElementById('loading-spinner').style.display = 'block'; 
            document.getElementById('result').style.display = 'none'; 
        }
    
        function hideLoadingSpinner() {
            document.getElementById('loading-spinner').style.display = 'none';
        }
    
        function showError(message) {
            document.getElementById('result').innerHTML = `<p class="alert alert-danger">${message}</p>`;
            document.getElementById('result').style.display = 'block'; 
        }
    
        function displayClientData(data) {
            const table = `
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Nombre</td><td>${data.nombre}</td></tr>
                        <tr><td>Apellido</td><td>${data.apellido}</td></tr>
                        <tr><td>DNI</td><td>${data.dni}</td></tr>
                        <tr><td>Correo electrónico</td><td>${data.correo_electronico}</td></tr>
                        <tr><td>Teléfono</td><td>${data.telefono}</td></tr>
                        <tr><td>Dirección</td><td>${data.direccion}</td></tr>
                    </tbody>
                </table>`;
            document.getElementById('result').innerHTML = table;
            document.getElementById('result').style.display = 'block'; 
        }
    
        function displayAccessoryData(data) {
            const table = `
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(item => `
                            <tr><td>Nombre</td><td>${item.nombre}</td></tr>
                            <tr><td>Descripción</td><td>${item.descripcion}</td></tr>
                            <tr><td>Stock</td><td>${item.stock}</td></tr>
                            <tr><td>Precio</td><td>${item.precio}</td></tr>
                        `).join('')}
                    </tbody>
                </table>`;
            document.getElementById('result').innerHTML = table;
            document.getElementById('result').style.display = 'block'; 
        }
    
        function displayDeviceData(data) {
            const table = `
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(item => `
                            <tr><td>Marca</td><td>${item.marca}</td></tr>
                            <tr><td>Modelo</td><td>${item.modelo}</td></tr>
                            <tr><td>Número de Serie</td><td>${item.numero_de_serie}</td></tr>
                        `).join('')}
                    </tbody>
                </table>`;
            document.getElementById('result').innerHTML = table;
            document.getElementById('result').style.display = 'block'; 
        }
    
        async function fetchResults(type, query) {
            const url = getUrl(type, query);
            if (!url) {
                showError('Tipo de búsqueda no válido.');
                return;
            }
    
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error("Error en la búsqueda.");
                }
    
                const data = await response.json();
                hideLoadingSpinner();
    
                if (type === 'clientes') {
                    displayClientData(data);
                } else if (type === 'dispositivos') {
                    displayDeviceData(data);
                } else if (type === 'accesorios') {
                    displayAccessoryData(data);
                } else {
                    showError('Tipo de búsqueda no válido.');
                }
            } catch (error) {
                hideLoadingSpinner();
                showError(error.message);
            }
        }
    
        function getUrl(type, query) {
            switch (type) {
                case 'clientes':
                    return `ajax/get_client.php?query=${encodeURIComponent(query)}`;
                case 'dispositivos':
                    return `ajax/get_dispositivos.php?query=${encodeURIComponent(query)}`;
                case 'accesorios':
                    return `ajax/get_accesorios.php?query=${encodeURIComponent(query)}`;
                default:
                    console.error('Tipo de búsqueda no válido:', type);
                    return null;
            }
        }
    </script>

    <nav class="menu text-center">
        <a href="vista_nav/accesorios.html">Accesorios</a>
        <a href="vista_nav/reparacion_computadoras.html">Reparación Computadoras</a>
        <a href="vista_nav/reparacion_celulares.html">Reparación Celulares</a>
        <a href="vista_nav/reparacion_electronica.html">Reparación Electrónica</a>

    </nav>

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-target="#carouselExampleIndicators" data-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-target="#carouselExampleIndicators" data-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-target="#carouselExampleIndicators" data-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="presentacion/reparacion-computadoras.jpg" class="d-block w-100" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="presentacion/reparacion_de_celulares.jpg" class="d-block w-100" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="presentacion/reparacion_electronica.jpg" class="d-block w-100" alt="Imagen 3">
            </div>
            <div class="carousel-item">
                <img src="presentacion/abretucompu.jpg" class="d-block w-100" alt="Imagen 4">
            </div>
            <div class="carousel-item">
                <img src="presentacion/6.png" class="d-block w-100" alt="Imagen 5">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
        </a>
    </div>
    
    <nav class="navbar navbar-expand-lg navbar-light mt-3">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="container my-5">
        <div class="row">
            <div class="col-md-4">
                <img src="presentacion/3.png" class="img-fluid" alt="Producto 1">
                <p class="text-center">Accesorios</p>
            </div>
            <div class="col-md-4">
                <img src="presentacion/3.png" class="img-fluid" alt="Producto 2">
                <p class="text-center">Celulares</p>
            </div>
            <div class="col-md-4">
                <img src="presentacion/3.png" class="img-fluid" alt="Producto 3">
                <p class="text-center">Computadoras</p>
            </div>
        </div>
    </section>

    <section class="payment-options my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 text-center">
                    <strong>Pagá en cuotas</strong>
                    <div><a href="#">Ver promociones bancarias</a></div>
                    <img src="presentacion/logo.png " alt="Cupon 1" class="qr-code">
                </div>
                <div class="col-md-3 text-center">
                    <strong>¿Necesitás financiación?</strong>
                    <div><a href="#">Conocé nuestras opciones</a></div>
                    <img src="presentacion/logo.png" alt="Cupon 2" class="qr-code">
                </div>
                <div class="col-md-3 text-center">
                    <strong>Trabajamos con</strong>
                    <div><a href="#">Ver bancos</a></div>
                    <img src="presentacion/logo.png" alt="Cupon 3" class="qr-code">
                </div>
            </div>
        </div>
    </section>

    <div class="whatsapp-bubble">
        <a href="https://wa.me/5491122334455" target="_blank">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
        </a>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Marquez Comunicaciones. Todos los derechos reservados.</p>
        <div>
            <a href="#">Política de privacidad</a> | 
            <a href="#">Términos y condiciones</a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script para la funcionalidad de búsqueda -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("search-input");
            const searchButton = document.getElementById("search-button");
            const searchResults = document.getElementById("search-results");
            const productItems = document.querySelectorAll(".product-item");

            function searchProducts() {
                const query = searchInput.value.trim().toLowerCase();
                searchResults.innerHTML = "";  // Limpiar resultados anteriores
                let resultsFound = false;

                productItems.forEach(function(item) {
                    const productName = item.getAttribute("data-name").toLowerCase();
                    if (productName.includes(query) && query !== "") {
                        const resultItem = document.createElement("div");
                        resultItem.className = "search-result-item";
                        resultItem.textContent = item.getAttribute("data-name");
                        searchResults.appendChild(resultItem);
                        resultsFound = true;
                    }
                });

                if (resultsFound) {
                    searchResults.style.display = "block";
                } else {
                    searchResults.style.display = "none";
                }
            }

            searchButton.addEventListener("click", searchProducts);

            searchInput.addEventListener("keyup", function(event) {
                if (event.key === "Enter") {
                    searchProducts();
                }
            });
        });
    </script>
</body>
</html>
