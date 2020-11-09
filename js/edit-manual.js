$('#loading').hide();

$('#add-manual').on('click', function (event) {
    event.preventDefault();
    action = $(this).attr('action');
    profesor = $('#fichar-manual').val();
    fecha = $('#add-fecha').val();
    horaEntrada = $('#add-hora-entrada').val();
    horaSalida = $('#add-hora-salida').val();

    data = {
        'action': action,
        'ID': profesor,
        'fecha': fecha,
        'horaEntrada': horaEntrada,
        'horaSalida': horaSalida
    };

    urlPath = 'index.php?ACTION=fichar-mysql-manual';
    console.log(urlPath);
    $.ajax({
        url: urlPath,
        type: "POST",
        data: data,
        beforeSend: function () {
            $('#file-content-modal').modal('hide'),
            $('#loading-msg').html('Cargando...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
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
                toastr["error"]("El profesor "+nombre+" ya ha fichado el día "+fecha+".", "Error!")
            } else if (data.match('Error-Festivo')) {
                toastr["error"]("No se puede fichar en dias no lectivos.", "Error!")
            }else if (data.match('Error-Formato-Fecha')) {
                toastr["error"]("El formato de fecha no es correcto.", "Error!")
            }else {
                toastr["error"]("Error inesperado...", "Error!")
            }
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html(e);
        }
    });
});