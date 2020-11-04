$('.hide-it').hide();
$('#loading').hide();

$('.edit').on('click', function () {
    fieldSplit = $(this).attr('fields').split('_');
    fieldData = fieldSplit[1];

    txt = $('#txt_'+fieldData).html();

    $('#input_'+fieldData).val(txt).toggle();
    $('#txt_'+fieldData).toggle();
    $('#btn_'+fieldData).toggle();
});


$('.update').on('click', function () {
    fieldId = $(this).attr('data');
    curso = $('#input_'+fieldId).val();
    action = 'update';

    urlPath = 'index.php?ACTION=horarios&OPT=edit-cursos&action='+action+'&curso='+curso+'&data='+fieldId;
    console.log(urlPath);
    $.ajax({
        url: urlPath,
        type: "GET",
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file-content-modal').modal('hide'),
            $('#loading-msg').html('Cargando...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
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

$('#add-btn').on('click', function () {
    action = $(this).attr('action');
    curso = $('#add-curso').val();

    urlPath = 'index.php?ACTION=horarios&OPT=edit-cursos&action='+action+'&curso='+curso;
    console.log(urlPath);
    $.ajax({
        url: urlPath,
        type: "GET",
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file-content-modal').modal('hide'),
            $('#loading-msg').html('Cargando...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
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

    urlPath = 'index.php?ACTION=horarios&OPT=edit-cursos&action='+action+'&data='+fieldId;
    console.log(urlPath);
    $.ajax({
        url: urlPath,
        type: "GET",
        data: {},
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#file-content-modal').modal('hide'),
            $('#loading-msg').html('Cargando...');
            $('#loading').show();
            $('#loading').css('z-index', 99);
        },
        success: function (data) {
            if (data.match('Ok-action')) {
                toastr["success"]("Curso eliminado correctamente.", "Correcto!"),
                $('#fila_'+fieldId).remove()
            } else if (data.match('Error-remove')) {
                toastr["error"]("Error al eliminar curso.", "Error!")
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