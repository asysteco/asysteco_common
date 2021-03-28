$(document).on('click', '.actualiza', function () {
    let profesor = $(this).attr('data-id');
    let date =$(this).attr('data-date');
    let hour = $(this).attr('data-hour');
    let inicio = $(this).attr('data-startHour');
    let fin = $(this).attr('data-endHour');
    let type = $(this).attr('data-type');
    let action = $(this).attr('data-action');
    let value = $(this).attr('data-value');
    data = {
        profesor: profesor,
        date: date,
        hour: hour,
        inicio: inicio,
        fin: fin,
        type: type,
        action: action,
        value: value
    };
    urlPath = 'index.php?ACTION=marcajes&OPT=update';

    $.ajax({
        url: urlPath,
        type: 'POST',
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (response) {
            rData = JSON.parse(response);

            if (rData.success) {
                toastr["success"](rData.msg, "Correcto!");
                getRow(profesor, date, hour);
            } else {
                toastr["error"](rData.msg, "Error!");
                loadingOff();
            }
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!");
            loadingOff();
        }
    });
});

function getRow (profesor, date, hour){
    $.ajax({
        url: 'index.php?ACTION=marcajes&OPT=getRow',
        type: 'GET',
        data: {
            profesor: profesor,
            date: date,
            hour: hour,
        },
        success: function (response) {
            let rData = JSON.parse(response);

            if (rData.success) {
                $('#fila_'+profesor+'_'+date+'_'+hour).replaceWith(rData.data);
            } else {
                toastr["error"](rData.msg, "Error!");
            }
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!");
            loadingOff();
        }
    });
}