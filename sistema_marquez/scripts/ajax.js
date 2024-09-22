// Funciones AJAX

function hacerSolicitud(url, metodo, datos, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open(metodo, url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            callback(xhr.responseText);
        }
    };

    xhr.send(datos);
}
