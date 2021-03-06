$(document).on('click', '.actualiza', function () {
    botonpulsado = $(this);
    datos = $(this).attr('asiste').split(',');
    Profesor = datos[0];
    Fecha = datos[1];
    Hora = datos[2];
    act = datos[3];
    Valor = datos[4];
    data = {
        'Profesor': Profesor,
        'Fecha': Fecha,
        'Hora': Hora,
        'act': act,
        'Valor': Valor
    };
    urlPath = 'index.php?ACTION=marcajes&OPT=update';

    $.ajax({
        url: urlPath,
        type: 'GET',
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (response) {
            if (response.match('Ok-asiste')){
                toastr["success"]("Petición realizada correctamente.", "Correcto!")
            } else if (response.match('Ok-falta')){
                toastr["success"]("Petición realizada correctamente.", "Correcto!")
            } else if (response.match('Ok-extraescolar')){
                toastr["success"]("Petición realizada correctamente.", "Correcto!")
            } else if (response.match('Ok-justificada')){
                toastr["success"]("Petición realizada correctamente.", "Correcto!")
            } else if (response.match('Ok-injustificada')){
                toastr["success"]("Petición realizada correctamente.", "Correcto!")
            } else {
                toastr["error"]("Error inesperado...", "Error!")
            } 
            getRow(Profesor, Fecha, Hora);
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!")
        }
    });
});

function getRow (Profesor, Fecha, Hora){
    $.ajax({
        url: 'index.php?ACTION=asistencias',
        type: 'GET',
        data: {
            'ID': Profesor,
            'Fecha': Fecha,
            'Hora': Hora,
            'act': 'getrow',
        },
        success: function (response) {
            $('#fila_'+Profesor+'_'+Fecha+'_'+Hora).replaceWith($('#fila_'+Profesor+'_'+Fecha+'_'+Hora,response))
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!")
        }
    });
}