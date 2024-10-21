$(document).ready(function () {
    // Escuchar cambios en el select del tipo de comprobante
    $('#id_tipo_comprobante').change(function () {
        // Obtener el valor del tipo de comprobante seleccionado
        var tipoComprobante = $(this).val();
        var subtotal = parseFloat($('#subtotal').val()); // Obtener el subtotal
        var iva = 0;
        var total = subtotal;

        // Si el tipo de comprobante es "Factura A" (suponiendo id 1 es "Factura A")
        if (tipoComprobante == 1) { // Asegúrate de que el ID 1 corresponde a Factura A
            iva = subtotal * 0.21; // Calcular el IVA 21%
            total = subtotal + iva;

            // Mostrar los resultados del IVA y el total
            $('#iva_resultados').show();
            $('#iva_resultado').val(iva.toFixed(2)); // Mostrar IVA con 2 decimales
            $('#total_resultados').show();
            $('#total').val(total.toFixed(2)); // Mostrar total con 2 decimales
        } else {
            // Si no es "Factura A", ocultar los campos de IVA y Total
            $('#iva_resultados').hide();
            $('#total_resultados').hide();

            // Mostrar un mensaje
            $('#resultado').html("<p>Este tipo de comprobante no requiere IVA.</p>");
        }
    });
});
