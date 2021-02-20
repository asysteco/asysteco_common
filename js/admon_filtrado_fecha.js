$('#fechainicio, #fechafin').keypress(function (e) {
    e.preventDefault()
});

var fechaInicio = $('#fechainicio').val();
var fechaFin = $('#fechafin').val();
var profesor = $('#select_profesor').val();
var action = '';
var titulo;

$('#fechainicio').on('change', function () {
    fechaInicio = $(this).val(),
        $(function () {
            $('#fechafin').datepicker().focus();
        });
});

$('#fechafin').on('change', function () {
    fechaFin = $(this).val();
});

$('#select_profesor').on('change', function () {
    profesor = $(this).val();
});

$(document).on('click', '.act', function () {
    titulo = $(this).attr('data-name');
    element = $(this).attr('data-item');
    action = $(this).attr('action');
    urlPath = 'index.php?ACTION=admon&OPT=select';
    data = {
        'action': action,
        'element': element,
        'fechainicio': fechaInicio,
        'fechafin': fechaFin,
        'profesor': profesor,
        'pag': 0
    };

    if (action === '') {
        toastr['error']("No se puede realizar dicha acción.", "Error!");
        return;
    }

    $.ajax({
        url: urlPath,
        type: 'GET',
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (data) {
            if (data.match('^Error-mkdir$')) {
                toastr["error"]("Error al exportar...", "Error!");
                loadingOff();
                return;
            } else if (data.match('^No-data$')) {
                toastr["error"]("No existen datos para exportar.", "Error!");
                loadingOff();
                return;
            }

            if (action === 'select') {
                $('#modal-titulo').html('Listado de ' + titulo);
                $('#btn-response').html(data);
                $('#modal-admon').modal('show');
                loadingOff();
            } else if (action === 'export') {
                window.open(data);
                setTimeout(() => { CheckBackupFile(element) }, 500);
            }
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!")
        }
    });
});

// Paginación:

$(document).ready(function () {
    loadingOff();
});

$(document).on('change', '#select_pag', function() {
    element = $(this).children().attr('element');
    action = $(this).children().attr('action');
    page = $(this).val();
    profesor = $(this).children().attr('profesor');
    start = $(this).children().attr('start');
    end = $(this).children().attr('end');
    urlPath = 'index.php?ACTION=admon&OPT=select';
    data = {
        'action': action,
        'element': element,
        'profesor': profesor,
        'fechainicio': start,
        'fechafin': end,
        'pag': page
    };
    
    $.ajax({
        url: urlPath,
        type: 'GET',
        data:  data,
        beforeSend : function() {
            loadingOn();
        },
        success: function(data) {
            $('#btn-response').html(data);
            loadingOff();
        },
        error: function(e) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html(e);
        }          
    });
});