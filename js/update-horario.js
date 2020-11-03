$('.entrada').hide();

$(document).ready(function () {
    $('#loading').fadeOut();
});

$(window).click(function () {
    $('.entrada').hide(),
        $('.txt').show()
});

$('.entrada, .txt').click(function (event) {
    event.stopPropagation()
});

$('.txt').on('click', function () {
    texto = $(this).html(),
        datos = $(this).attr('id').split('_'),
        id = datos[1],
        columna = datos[2],
        $(this).hide();
    if (columna == 'Aula') {
        tx = '#in_' + id + '_' + columna
    }
    else {
        tx = '#in2_' + id + '_' + columna
    }
    $(tx).val(texto),
        $(tx).show().focus()
});

$('.entrada').on('change', function () {
    texto = $(this).val(),
        datos = $(this).attr('id').split('_'),
        id = datos[1],
        columna = datos[2],
        $(this).hide();
    if (columna == 'Aula') {
        sp = '#sp_' + id + '_' + columna
    }
    else {
        sp = '#sp2_' + id + '_' + columna
    }
    $(sp).html(texto),
    $(sp).show(),
    enlace = "index.php?ACTION=horarios&OPT=update&SUBOPT=t-horario&id=" + id + "&columna=" + columna + "&texto=" + texto,
    $.ajax({
        url: enlace,
        type: 'GET',
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file-content-modal').modal('hide'),
                $('#loading-msg').html('Cargando Horario...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
        },
        success: function (data) {
            if (data.match('Error-add')) {
                toastr["error"]("Error al añadir hora.", "Error!")
            } else if (data.match('Error-remove')) {
                toastr["error"]("Error al eliminar hora.", "Error!")
            } else if (data.match('Error-params')) {
                toastr["error"]("Error, parámetros no válidos.", "Error!")
            } else {
                toastr["success"]("Acción realizada correctamente.", "Correcto!"),
                setTimeout(function () { location.reload() }, 700)
            }
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
                $('#error-content-modal').html(e);
        }
    });
});

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
                $('#loading-msg').html('Cargando Horario...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
        },
        success: function (data) {
            if (data.match('Error-add')) {
                toastr["error"]("Error al añadir hora.", "Error!")
            } else if (data.match('Error-remove')) {
                toastr["error"]("Error al eliminar hora.", "Error!")
            } else if (data.match('Error-params')) {
                toastr["error"]("Error, parámetros no válidos.", "Error!")
            } else {
                toastr["success"]("Acción realizada correctamente.", "Correcto!"),
                setTimeout(function () { location.reload() }, 700)
            }
            $('#loading').fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
                $('#error-content-modal').html(e);
        }
    });
});