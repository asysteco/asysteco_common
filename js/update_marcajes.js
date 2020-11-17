$('#loading').hide();

$(document).ready(function() {
    RefreshEvent();
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
            console.log(response),
            $('#fila_'+Profesor+'_'+Fecha+'_'+Hora).replaceWith($('#fila_'+Profesor+'_'+Fecha+'_'+Hora,response))
        },
        error: function (e) {
            $("#err").html(e).fadeIn();
        }
    });
    RefreshEvent();
}

function RefreshEvent() {

    $('body').on('click', '.actualiza', function () {
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
                $("#loading-msg").html("Realizando petición...");
                $("#loading").show();
                $("#loading").css('z-index', 99);
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
                } else if (response.match('Ok-injustificado')){
                    toastr["success"]("Petición realizada correctamente.", "Correcto!")
                } else {
                    toastr["error"]("Error inesperado...", "Error!")
                } 
                getRow(Profesor, Fecha, Hora)
                $("#loading").fadeOut();
            },
            error: function (e) {
                $("#err").html(e).fadeIn();
            }
        });
    
        // if($('#marcaje-response').load('index.php?ACTION=marcajes&OPT=update&Profesor='+Profesor+'&Fecha='+Fecha+'&Hora='+Hora+'&act='+act+'&Valor='+Valor))
        // {
        //     $('#table-container').hide(),
        //     setTimeout(function(){location.reload()}, 200)
        // }
        // else
        // {
        //     $('#marcaje-response').html('Error')
        // }
    });
}

