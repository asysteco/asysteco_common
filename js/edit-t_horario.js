var registrosUpdate = [];

$('.remove').hover(function(){
    $(this).css('color', 'red');
    $(this).css('padding', '10px');
    $(this).css('transform', 'scale(1.3)');
    $(this).css('cursor', 'pointer');
}, function(){
    $(this).css('color', 'black');
    $(this).css('transform', 'scale(1)');
});

$('body').on('click', '.act', function () {
    action = $(this).attr('action');
    urlPath = 'index.php?ACTION=horarios&OPT=edit-t_horario';
    date = $('#program-date').attr('data');
    realDate = $('#program-date').attr('data-real');

    if (action === 'add') {
        tipo = $('#add-tipo').val();
        dia = $('#add-dia').val();
        edificio = $('#add-edificio').val();
        hora = $('#add-hora-' + tipo).val();
        aula = $('#add-aula').val();
        curso = $('#add-curso').val();
        profesor = $('#profesor').attr('data');
        if (dia == '' || hora == ''  || aula == ''  || curso == '') {
            toastr["warning"]("Debe seleccionar todos los campos.", "Error!");
            return;
        }

        data = {
            action: action,
            tipo: tipo,
            dia: dia,
            edificio: edificio,
            hora: hora,
            aula: aula,
            curso: curso,
            profesor: profesor,
            date: date
        };
    } else if (action === 'update') {
        profesor = $('#profesor').attr('data');
        data = {
            'action': action,
            'datos': registrosUpdate,
            'profesor': profesor
        };
    } else if (action === 'remove') {
        rowId = $(this).attr('data');
        profesor = $('#profesor').attr('data');
        confirmAnswer = confirm('¿Desea eliminar esta hora?');
        if (!confirmAnswer) {
            return;
        }
        
        data = {
            'action': action,
            'rowId': rowId,
            'profesor': profesor
        };
    } else if (action === 'cancel') {
        confirmAnswer = confirm('¿Desea cancelar los cambios del horario?');
        if (!confirmAnswer) {
            return;
        }
        
        location.reload();
        return;
    } else if (action === 'cancel-program') {
        profesor = $('#profesor').attr('data');
        confirmAnswer = confirm('¿Desea cancelar los cambios programados?');
        if (!confirmAnswer) {
            return;
        }
        
        data = {
            'action': action,
            'date': date,
            'profesor': profesor
        };
    } else if (action === 'apply-program') {
        confirmAnswer = confirm('¿Desea programar este horario para el día '+realDate+'?');
        if (!confirmAnswer) {
            return;
        }
        
        toastr["success"]("Horario programado.", "Correcto!"),
        setTimeout(function () { location.href = 'index.php?ACTION=profesores' }, 700);
        return;
    } else {
        toastr["error"]("Acción no válida.", "Error!");
        return;
    }

    $.ajax({
        url: urlPath,
        type: "POST",
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (data) {
            response = JSON.parse(data);

            if (response.success) {
                toastr["success"](response.msg, "Correcto!");
            } else {
                toastr["error"](response.msg, "Error!");
            }

            if (response.reload) {
                setTimeout(() => { location.reload() }, 700);
                return;
            }

            if (response.trigger) {
                if (response.trigger === 'remove-hora') {
                    $('#fila_'+rowId).remove()
                }

                if (response.trigger === 'go-to') {
                    setTimeout(function () { location.href = 'index.php?ACTION=profesores' }, 700);
                    return;
                }

                if (response.trigger === 'updated') {
                    $('.update').removeAttr('disabled');
                    registrosUpdate = [];
                    $('#apply-program, #cancel-program').fadeIn();
                    $('#update-btn, #cancel-btn').fadeOut();
                }
            }
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!")
        }
    });
});

$('.update').on('change', function() {
    $(this).attr('disabled', 'disabled');
    $('#apply-program, #cancel-program').fadeOut();
    $('#update-btn, #cancel-btn').fadeIn();
    registerId = $(this).attr('data-info');
    field = $(this).attr('data-field');
    value = $(this).val();
    data = [
        registerId,
        field,
        value
    ];
    registrosUpdate.push(data);
});

$(document).on('change', '#add-tipo', function() {
    tipo = $(this).val();
    $('.select-hora').hide();
    $('#add-hora-' + tipo).css('display', 'inline-block');
});