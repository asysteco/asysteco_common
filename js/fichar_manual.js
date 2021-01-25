$(function () {
    $('.fichajeEntrada').timepicker();
    if ($(".fichajeSalida")[0]) {
        $('.fichajeSalida').timepicker();
    }
});

$('#add-manual').on('click', function (event) {
    event.preventDefault();
    action = $(this).attr('action');
    profesor = $('#fichar-manual').val();
    fecha = $('#add-fecha').val();
    horaEntrada = $('#add-hora-entrada').val();
    horaSalida = $('#add-hora-salida').val();

    if (action == '' || profesor == '' || fecha == '' || horaEntrada == '') {
        toastr["warning"]("Debe rellenar todos los campos.", "Aviso!")
        return;
    }

    data = {
        'action': action,
        'ID': profesor,
        'fecha': fecha,
        'horaEntrada': horaEntrada,
        'horaSalida': horaSalida
    };

    urlPath = 'index.php?ACTION=fichar-mysql-manual';
    $.ajax({
        url: urlPath,
        type: "POST",
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (data) {
            if (data.match('Ok-action')) {
                toastr["success"]("Fichaje Añadido Correctamente.", "Correcto!"),
                $('#add-fecha').val(''),
                $('#add-hora-entrada').val(''),
                $('#add-hora-salida').val('')
            } else if (data.match('Error-Insert')) {
                toastr["error"]("Error al añadir el Fichaje.", "Error!")
            } else if (data.match('Error-Ya-Fichado')) {
                nombre = $( "#fichar-manual option:selected" ).text();
                toastr["error"](nombre+" ya ha fichado el día "+fecha+".", "Error!")
            } else if (data.match('Error-Festivo')) {
                toastr["error"]("No se puede fichar en dias no lectivos.", "Error!")
            }else if (data.match('Error-Formato-Fecha')) {
                toastr["error"]("El formato de fecha no es correcto.", "Error!")
            }else {
                toastr["error"]("Error inesperado...", "Error!")
            }
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!")
        }
    });
});