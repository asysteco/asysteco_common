$('.eliminar').on('click', function() {
    var elemento = $(this).attr('elemento');

    if (elemento === 'profesores') {
        warning = "Esta acción eliminará todos y cada uno de los profesores, sus fichajes realizados y sus marcajes por hora del sistema. Estos cambios serán irreversibles.\n"+
        "¿Está seguro de continuar?";
    } else if (elemento === 'horarios') {
        warning = "Esta acción eliminará todos y cada uno de los horarios del sistema. Estos cambios serán irreversibles.\n"+
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
        $("#loading").css('z-index', 99);
    },
    success: function(data) {
        if (data.match('Error-horarios')) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html('<h3>Error al eliminar los horarios.</h3>')
        } else if (data.match('Error-temp-horarios')) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html('<h3>Error al eliminar los horarios programados.</h3>')
        } else if (data.match('Error-fichar')) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html('<h3>Error al eliminar los fichajes.</h3>')
        } else if (data.match('Error-profesores')) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html('<h3>Error al eliminar los profesores.</h3>')
        } else {
            $('#fine-modal').modal('show'),
            $('#fine-content-modal').html('<h3>¡Datos eliminados con éxito!</h3>');
            // setTimeout(function(){location.reload()}, 700);
        }
        $("#loading").fadeOut();
    },
        error: function(e) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html(e);
        }          
    });
});