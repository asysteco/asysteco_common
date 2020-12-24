$('.hide-it').hide();
var clickedButton = '';
var clickedId = '';

function hideShow() {
    $('.hide-it').hide();
    $('.show-it').show()
}
$('.edit').hover(function(){
    $(this).css('color', 'green');
    $(this).css('transform', 'scale(1.3)');
    $(this).css('cursor', 'pointer');
}, function(){
    $(this).css('color', 'black');
    $(this).css('transform', 'scale(1)');
});
$('.remove').hover(function(){
    $(this).css('color', 'red');
    $(this).css('transform', 'scale(1.3)');
    $(this).css('cursor', 'pointer');
}, function(){
    $(this).css('color', 'black');
    $(this).css('transform', 'scale(1)');
});

$(window).click(function() {
    hideShow();
});

$('.edit, .hide-it, show-it').click(function(event){
    event.stopPropagation()
});

$('.edit').on('click', function () {
    fieldSplit = $(this).attr('fields').split('_');
    fieldData = fieldSplit[1];
    if (clickedId !== fieldData) {
        hideShow();
    }
    clickedButton = $(this).attr('fields');
    clickedId = fieldData;

    txt = $('#txt_'+fieldData).html();

    $('#input_'+fieldData).val(txt).toggle();
    $('#input_'+fieldData).focus();
    $('#txt_'+fieldData).toggle();
    $('#btn_'+fieldData).toggle();
});

$(".hide-it").keyup(function(event) {
    console.log(event);
    if (event.keyCode === 13) {
        data = $(this).attr('id').split('_');
        elementId = data[1];
        $('#btn_'+elementId).click();
    } else if(event.keyCode === 27) {
        hideShow();
    }
});

$('.update').on('click', function () {
    fieldId = $(this).attr('data');
    curso = $('#input_'+fieldId).val();
    action = 'update';
    data = {
        'action': action,
        'curso': curso,
        'data': fieldId
    };
    urlPath = 'index.php?ACTION=horarios&OPT=edit-cursos';
    
    $.ajax({
        url: urlPath,
        type: "POST",
        data: data,
        beforeSend: function () {
            $('#loading-msg').html('Cargando...');
            $('#loading').show();
        },
        success: function (data) {
            if (data.match('Ok-action')) {
                toastr["success"]("Curso actualizado correctamente.", "Correcto!"),
                $('#input_'+fieldId).toggle(),
                $('#btn_'+fieldId).toggle(),
                $('#txt_'+fieldId).html(curso),
                $('#txt_'+fieldId).toggle()
            } else if (data.match('Error-exist')) {
                toastr["error"]("Ya existe un curso con este nombre.", "Error!")
            } else if (data.match('Error-update')) {
                toastr["error"]("Error al actualizar curso.", "Error!")
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

$("#add-curso").keyup(function(event) {
    if (event.keyCode === 13) {
        data = $(this).attr('id').split('_');
        elementId = data[1];
        $('#add-btn').click();
    }
});

$('#add-btn').on('click', function () {
    action = $(this).attr('action');
    curso = $('#add-curso').val();
    data = {
        'action': action,
        'curso': curso
    };
    urlPath = 'index.php?ACTION=horarios&OPT=edit-cursos';

    $.ajax({
        url: urlPath,
        type: "POST",
        data: data,
        beforeSend: function () {
            $('#loading-msg').html('Cargando...');
            $('#loading').show();
        },
        success: function (data) {
            if (data.match('Ok-action')) {
                toastr["success"]("Curso añadido correctamente.", "Correcto!"),
                setTimeout(function () { location.reload() }, 700)
            } else if (data.match('Error-exist')) {
                toastr["error"]("Ya existe un curso con este nombre.", "Error!")
            } else if (data.match('Error-add')) {
                toastr["error"]("Error al añadir curso.", "Error!")
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

$('.remove').on('click', function () {
    fieldId = $(this).attr('data');
    action = $(this).attr('action');
    nombreCurso = $('#txt_'+fieldId).html();
    data = {
        'action': action,
        'data': fieldId
    };
    urlPath = 'index.php?ACTION=horarios&OPT=edit-cursos';

    if (!confirm('¿Estás seguro/a de eliminar del curso '+nombreCurso+'?')) {
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
            if (data.match('Ok-action')) {
                toastr["success"]("Curso eliminado correctamente.", "Correcto!"),
                $('#fila_'+fieldId).remove()
            } else if (data.match('Error-delete')) {
                toastr["error"]("Error al eliminar curso. Asegúrese de que no esté en uso en ningún horario.", "Error!")
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