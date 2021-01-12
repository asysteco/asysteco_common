$('#update-btn').hide();
$('#cancel-btn').hide();
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
    urlPath = 'index.php?ACTION=horarios&OPT=edit-horario';

    if (action === 'add') {
        dia = $('#add-dia').val();
        edificio = $('#add-edificio').val();
        hora = $('#add-hora').val();
        aula = $('#add-aula').val();
        curso = $('#add-curso').val();
        profesor = $('#profesor').attr('data');
        if (dia == '' || hora == ''  || aula == ''  || curso == '') {
            toastr["warning"]("Debe seleccionar todos los campos.", "Aviso!");
            return;
        }

        data = {
            'action': action,
            'dia': dia,
            'edificio': edificio,
            'hora': hora,
            'aula': aula,
            'curso': curso,
            'profesor': profesor
        };
    } else if (action === 'program') {
        profesor = $('#profesor').attr('data');
        programDate = $('#fecha-programar-horario').val();

        if (programDate !== '' && programDate !== undefined && /^(0[1-9]|1[0-9]|2[0-9]|3[01])\/(0[1-9]|1[0-2])\/(19[0-9]{2}|20[0-9]{2})$/i.test(programDate)) {
            location.href = location.href+'&programDate='+programDate;
            return;
        } else if (programDate === '') {
            toastr["warning"]("Debe seleccionar una fecha", "Aviso!");
            $('#fecha-programar-horario').focus();
            return;
        } else {
            toastr["warning"]("Formato de fecho incorrecto. Formato aceptado: DD/MM/AAAA", "Aviso!");
            $('#fecha-programar-horario').focus();
            return;
        }

    } else if (action === 'update') {
        profesor = $('#profesor').attr('data');
        data = {
            'action': action,
            'datos': registrosUpdate,
            'profesor': profesor
        }
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
            if (data.match('Ok-add')) {
                toastr["success"]("Hora añadida correctamente.", "Correcto!");
                setTimeout(function () { location.reload() }, 700);
            } else if (data.match('Ok-update')) {
                toastr["success"]("Horas actualizadas correctamente.", "Correcto!");
                $('.update').removeAttr('disabled');
                registrosUpdate = [];
                $('#update-btn, #cancel-btn').fadeOut();
                // setTimeout(function () { location.reload() }, 700);
            } else if (data.match('Ok-remove')) {
                toastr["success"]("Hora eliminada correctamente.", "Correcto!"),
                $('#fila_'+rowId).remove()
            } else if (data.match('Error-add')) {
                toastr["error"]("Error al añadir hora.", "Error!")
            } else if (data.match('Error-duplicate')) {
                toastr["error"]("Error al añadir hora, no puedes duplicar horas con el mismo Aula y Curso.", "Error!")
            } else if (data.match('Error-update')) {
                toastr["error"]("Error al actualizar horas.", "Error!")
            } else if (data.match('Error-empty')) {
                toastr["error"]("Debe seleccionar todos los campos.", "Error!")
            } else if (data.match('Error-valid')) {
                toastr["error"]("Nombre de curso no válido.", "Error!")
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

$('.update').on('change', function() {
    $(this).attr('disabled', 'disabled');
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