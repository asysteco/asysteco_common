$('.eliminar').on('click', function() {
    var elemento = $(this).attr('elemento');

    if (elemento === 'profesores') {
        warning = "Esta acción eliminará todos y cada uno de los profesores, sus fichajes realizados y sus marcajes por hora del sistema. Estos cambios serán irreversibles.\n"+
        "¿Está seguro de continuar?";
    } else if (elemento === 'horarios') {
        warning = "Esta acción eliminará todos y cada uno de los horarios del sistema. Estos cambios serán irreversibles.\n"+
        "¿Está seguro de continuar?";
    } else if (elemento === 't-horarios') {
        warning = "Esta acción eliminará los horarios PROGRAMADOS EN UN FUTURO en el sistema. Estos cambios serán irreversibles.\n"+
        "¿Está seguro de continuar?";
    } else {
        return;
    }

    if (!confirm(warning)) {
        return;
    }

    urlPath = $(this).attr('enlace');
    $.ajax({  
    url: urlPath,
    type: 'GET',
    data:  {},
    contentType: false,
    cache: false,
    processData:false,
    beforeSend : function() {
        $('#file-content-modal').modal('hide'),
        $("#loading-msg").html("Eliminando datos...");
        $("#loading").show();
    },
    success: function(data) {
        if (data.match('Error-horarios')) {
            toastr["error"]("Error al eliminar los horarios.", "Error!")
        } else if (data.match('Error-temp-horarios')) {
            toastr["error"]("Error al eliminar los horarios programados.", "Error!")
        } else if (data.match('Error-fichar')) {
            toastr["error"]("Error al eliminar los fichajes.", "Error!")
        } else if (data.match('Error-profesores')) {
            toastr["error"]("Error al eliminar los profesores.", "Error!")
        } else {
            toastr["success"]("¡Datos eliminados con éxito!", "Correcto!")
        }
        $("#loading").fadeOut();
    },
        error: function(e) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html(e);
        }          
    });
});