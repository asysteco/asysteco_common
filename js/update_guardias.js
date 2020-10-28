

$(document).ready(function () {
    $('.modal-backdrop').remove(),
        $('#loading').fadeOut()
});

$('#select-edit-guardias').on('change', function () {
    profesor = $(this).val(),
        $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=' + profesor)
});

$('.act').hide();
$('.act').on('click', function () {
    urlPath = $(this).attr('enlace');
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file-content-modal').modal('hide'),
                $('#loading-msg').html('Cargando datos...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
        },
        success: function (data) {
            if (data.match('Error-add')) {
                $('#error-modal').modal('show'),
                    $('#error-content-modal').html('<h3>Error al añadir guardia.</h3>')
            } else if (data.match('Error-remove')) {
                $('#error-modal').modal('show'),
                    $('#error-content-modal').html('<h3>Error al eliminar guardia.</h3>')
            } else if (data.match('Error-params')) {
                $('#error-modal').modal('show'),
                    $('#error-content-modal').html('<h3>Error, parámetros no válidos.</h3>')
            } else if (data.match('Ok-add')) {
                $('#fine-modal').modal('show'),
                    $('#fine-content-modal').html('<h3>Guradia añadida correctamente.</h3>');
                    loadGuardias();
                // setTimeout(function () { location.reload() }, 700);
            } else if (data.match('Ok-remove')) {
                $('#fine-modal').modal('show'),
                    $('#fine-content-modal').html('<h3>Guradia eliminada correctamente.</h3>')
                    loadGuardias();
                // setTimeout(function () { location.reload() }, 700);
            } else {
                $('#error-modal').modal('show'),
                    $('#error-content-modal').html('<h3>Error inesperado...</h3>')
                // setTimeout(function(){location.reload()}, 700);
            }
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
                $('#error-content-modal').html(e);
        }
    });
});

$('.remove-guardia').on('click', function () {
    urlPath = $(this).attr('enlace');
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file-content-modal').modal('hide'),
                $('#loading-msg').html('Cargando datos...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
        },
        success: function (data) {
            if (data.match('Error-add')) {
                $('#error-modal').modal('show'),
                    $('#error-content-modal').html('<h3>Error al añadir guardia.</h3>')
            } else if (data.match('Error-remove')) {
                $('#error-modal').modal('show'),
                    $('#error-content-modal').html('<h3>Error al eliminar guardia.</h3>')
            } else if (data.match('Error-params')) {
                $('#error-modal').modal('show'),
                    $('#error-content-modal').html('<h3>Error, parámetros no válidos.</h3>')
            } else if (data.match('Ok-add')) {
                $('#fine-modal').modal('show'),
                    $('#fine-content-modal').html('<h3>Guradia añadida correctamente.</h3>');
                    loadGuardias();
                //setTimeout(function () { location.reload() }, 700);
            } else if (data.match('Ok-remove')) {
                $('#fine-modal').modal('show'),
                    $('#fine-content-modal').html('<h3>Guradia eliminada correctamente.</h3>')
                    loadGuardias();
                //setTimeout(function () { location.reload() }, 700);
            } else {
                $('#error-modal').modal('show'),
                    $('#error-content-modal').html('<h3>Error inesperado...</h3>')
                // setTimeout(function(){location.reload()}, 700);
            }
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

function loadGuardias() {
    urlPath = 'index.php?ACTION=horarios&OPT=guardias';
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
                $('#loading-msg').html('Cargando guardias...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
        },
        success: function (data) {
            $('#guardias-response').html(data);
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
                $('#error-content-modal').html(e);
        }
    });
}