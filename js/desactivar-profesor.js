var enlace;

$(document).ready(function (){
    $('#fecha-desactivar').datepicker({minDate: -5, maxDate: 0});
});

$(document).on('click', '.desactivar-profesor', function(e) {
    e.preventDefault();
    enlace = $(this).attr('enlace');
    $('#modal-desactivar').modal('show')
});

$(document).on('click', '.activar-profesor', function(e) {
    e.preventDefault();
    enlace = $(this).attr('enlace');
    $('#modal-activar').modal('show')
});

$('.act').on('click', function() {    
    action = $(this).attr('action');
    if (action === 'activar') {
        data = {
            action: action
        };
    } else if (action === 'desactivar') {
        fecha = $('#fecha-desactivar').val();
        if (fecha === '') {
            $('#fecha-desactivar').focus();
            toastr["warning"]("Debe seleccionar una fecha de desactivación.", "Advertencia!");
            return;
        }
        data = {
            action: action,
            fecha: fecha
        };
    } else {
        toastr["error"]("Acción no permitida.", "Error!");
        return;
    }
    $.ajax({
        url: enlace,
        type: "POST",
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (data) {
            if (data.match('activado')) {
                toastr["success"]("Profesor activado correctamente.", "Correcto!");
                setTimeout(function () { location.reload() }, 700);
            } else if (data.match('desact')) {
                toastr["success"]("Profesor desactivado correctamente.", "Correcto!");
                setTimeout(function () { location.reload() }, 700);
            } else if (data.match('error-activar')) {
                toastr["error"]("Error al activar profesor.", "Error!")
            } else if (data.match('error-desactivar')) {
                toastr["error"]("Error al desactivar profesor.", "Error!")
            } else if (data.match('error-fecha')) {
                toastr["error"]("Error en el formato de fecha.", "Error!")
            } else {
                toastr["error"]("Error inesperado...", "Error!")
            }
            loadingOff();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html(e);
        }
    });
});