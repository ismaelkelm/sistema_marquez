fetch('../base_datos/check_status.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({ 'order_number': orderNumber })
})
.then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok: ' + response.statusText);
    }
    return response.json();
})
.then(data => {
    if (data.error) {
        throw new Error(data.error); // Manejar errores específicos
    }
    statusResult.innerHTML = `<div class="status ${data.class}">${data.text}</div>`;
})
.catch(error => {
    console.error('Error:', error);
    statusResult.innerHTML = '<div class="alert alert-danger">Error al obtener el estado.</div>';
});
