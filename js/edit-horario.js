$('#loading').hide();
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
        hora = $('#add-hora').val();
        aula = $('#add-aula').val();
        curso = $('#add-curso').val();
        profesor = $('#profesor').attr('data');
        if (dia == '' || hora == ''  || aula == ''  || curso == '') {
            toastr["warning"]("Debe seleccionar todos los campos.", "Error!");
            return;
        }

        data = {
            'action': action,
            'dia': dia,
            'hora': hora,
            'aula': aula,
            'curso': curso,
            'profesor': profesor
        };
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
        toastr["success"]("Acción no válida.", "Correcto!");
        return;
    }
    
    $.ajax({
        url: urlPath,
        type: "POST",
        data: data,
        beforeSend: function () {
            $('#loading-msg').html('Cargando...');
            $('#loading').show();
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
            } else if (data.match('Error-update')) {
                toastr["error"]("Error al actualizar horas.", "Error!")
            } else if (data.match('Error-empty')) {
                toastr["error"]("Debe seleccionar todos los campos.", "Error!")
            } else if (data.match('Error-valid')) {
                toastr["error"]("Nombre de curso no válido.", "Error!")
            } else {
                toastr["error"]("Error inesperado...", "Error!")
            }
            $('#loading').fadeOut();
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