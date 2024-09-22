document.addEventListener("DOMContentLoaded", function() {
    // Selecciona el botón de cierre de sesión
    const logoutButton = document.querySelector(".btn-danger");

    // Asegúrate de que el botón exista antes de agregar el event listener
    if (logoutButton) {
        logoutButton.addEventListener("click", function(event) {
            event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

            // Realizar una petición AJAX para cerrar la sesión
            fetch('login/logout.php', {
                method: 'POST', // Usar el método POST
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ action: 'logout' }) // Pasar parámetros si es necesario
            })
            .then(response => {
                if (response.ok) {
                    // Redirigir a la página de inicio de sesión
                    window.location.href = '/mi_sistema/index.php';
                    // login/login.php';
                } else {
                    // Manejar errores si es necesario
                    console.error('Error al cerrar sesión');
                }
            })
            .catch(error => {
                console.error('Error en la petición de cierre de sesión:', error);
            });
        });
    }
});
