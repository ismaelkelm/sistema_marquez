// Función para buscar productos
function buscarProductos() {
    const query = document.getElementById('buscador').value;

    // Realizar la solicitud AJAX
    fetch(`buscar_productos.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => mostrarResultados(data))
        .catch(error => console.error('Error:', error));
}

// Función para mostrar los resultados en la página
function mostrarResultados(productos) {
    const resultadosDiv = document.getElementById('resultados');
    resultadosDiv.innerHTML = ''; // Limpiar resultados anteriores

    if (productos.length === 0) {
        resultadosDiv.innerHTML = '<p>No se encontraron productos.</p>';
        return;
    }

    productos.forEach(producto => {
        const productoDiv = document.createElement('div');
        productoDiv.className = 'producto';
        productoDiv.innerHTML = `
            <h3>${producto.nombre}</h3>
            <p>${producto.descripcion}</p>
            <p>Categoría: ${producto.categoria}</p>
            <p>Precio: $${producto.precio}</p>
        `;
        resultadosDiv.appendChild(productoDiv);
    });
}

// Agregar un evento al buscador para que llame a la función buscarProductos
document.getElementById('buscador').addEventListener('input', buscarProductos);
// Función para buscar productos
function buscarProductos() {
    const query = document.getElementById('buscador').value;

    // Realizar la solicitud AJAX
    fetch(`buscar_productos.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => mostrarResultados(data))
        .catch(error => console.error('Error:', error));
}

// Función para mostrar los resultados en la página
function mostrarResultados(productos) {
    const resultadosDiv = document.getElementById('resultados');
    resultadosDiv.innerHTML = ''; // Limpiar resultados anteriores

    if (productos.length === 0) {
        resultadosDiv.innerHTML = '<p>No se encontraron productos.</p>';
        return;
    }

    productos.forEach(producto => {
        const productoDiv = document.createElement('div');
        productoDiv.className = 'producto';
        productoDiv.innerHTML = `
            <h3>${producto.nombre}</h3>
            <p>${producto.descripcion}</p>
            <p>Categoría: ${producto.categoria}</p>
            <p>Precio: $${producto.precio}</p>
        `;
        resultadosDiv.appendChild(productoDiv);
    });
}

// Agregar un evento al buscador para que llame a la función buscarProductos
document.getElementById('buscador').addEventListener('input', buscarProductos);
