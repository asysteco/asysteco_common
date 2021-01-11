

$(document).ready(function () {
    $('.modal-backdrop').remove(),
        $('#loading').fadeOut()
});

$('#select-edit-guardias').on('change', function () {
    profesor = $(this).val(),
    loadGuardias(profesor);
});

$('.act').hide();
$('.act').on('click', function () {
    profesor = $('#profesor_act').attr('profesor');
    urlPath = $(this).attr('enlace');
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            overlayOn();
            $('#file-content-modal').modal('hide'),
            $('#loading-msg').html('Cargando guardias...');
            $('#loading').show();
        },
        success: function (data) {
            if (data.match('Error-add')) {
                toastr["error"]("Error al añadir guardia.", "Error!")
            } else if (data.match('Error-params')) {
                toastr["error"]("Error, parámetros no válidos.", "Error!")
            } else if (data.match('Ok-add')) {
                toastr["success"]("Guardia añadida correctamente.", "Correcto!");
                loadGuardias(profesor);
            } else {
                toastr["error"]("Error inesperado...", "Error!")
            }
            overlayOff();
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html(e);
        }
    });
});

$('.remove-guardia').on('click', function () {
    profesor = $('#profesor_act').attr('profesor');
    urlPath = $(this).attr('enlace');
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            overlayOn();
            $('#file-content-modal').modal('hide'),
            $('#loading-msg').html('Cargando guardias...');
            $('#loading').show();
        },
        success: function (data) {
            if (data.match('Error-remove')) {
                toastr["error"]("Error al eliminar guardia.", "Error!")
            } else if (data.match('Error-params')) {
                toastr["error"]("Error, parámetros no válidos.", "Error!")
            } else if (data.match('Ok-remove')) {
                toastr["success"]("Guardia eliminada correctamente.", "Correcto!");
                loadGuardias(profesor);
            } else {
                toastr["error"]("Error inesperado...", "Error!")
            }
            overlayOff();
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
                $('#error-content-modal').html(e);
        }
    });
});

$('.edificio').on('change', function () {
    edificio = $(this).val(),
        id = $(this).attr('id').split('-'),
        plus = 'plus-' + id[1] + '-' + id[2];
    if (edificio == '') {
        $('#' + plus).hide();
        return
    }
    else {
        enlace = $('#' + plus).attr('enlace'),
            $('#' + plus).attr('enlace', enlace + '&e=' + edificio),
            $('#' + plus).show()
    }
});

function loadGuardias(profesor) {
    urlPath = 'index.php?ACTION=horarios&OPT=guardias&profesor='+profesor;
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            overlayOn();
            $('#loading-msg').html('Cargando guardias...');
            $('#loading').show();
        },
        success: function (data) {
            $('#guardias-response').html(data);
            overlayOff();
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html(e);
        }
    });
}