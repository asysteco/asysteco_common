var modal = '<div id="info-horario-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">';
    modal += '<div class="modal-dialog modal-lg">';
        modal += '<div class="modal-content">';
            modal += '<div class="modal-header">';
                modal += '<h3 class="modal-title">Información de horarios</h3>';
                modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    modal += '<span aria-hidden="true">&times;</span>';
                modal += '</button>';
            modal += '</div>';
            modal += '<div class="modal-subtitle">';
                modal += '<h6 class="modal-title" style="color: grey; text-align: center;"><i>*Estos son los datos de horas disponibles para crear y/o editar un horario.</i></h6>';
                modal += '<h6 class="modal-title" style="color: grey; text-align: center;"><i>El Nº Referencia se utilizará como identificador de horas en los ficheros CSV a importar.</i></h6>';
            modal += '</div>';
            modal += '<div class="modal-body">';
                modal += '<div class="container-fluid">';
                    modal += '<div class="row">';
                        modal += '<div class="col-12">';
                            modal += '<div id="info-horario-body"></div>';
                        modal += '</div>';
                    modal += '</div>';
                modal += '</div>';
            modal += '</div>';
            modal += '<div class="modal-footer">';
                modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>';
            modal += '</div>';
        modal += '</div>';
    modal += '</div>';
modal += '</div>';

$('#info-horario').on('click', function() {
    urlPath = "index.php?ACTION=horarios&OPT=info";
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        beforeSend: function () {
            overlayOn();
            $("#loading-msg").html("Cargando...");
            $("#loading").show();
        },
        success: function (data) {
            $('body').append(modal);
            $('#info-horario-body').html(data);
            $('#info-horario-modal').modal('show');
            overlayOff();
            $("#loading").fadeOut();
        },
        error: function (e) {
            $('#error-modal').modal('show');
            $('#error-content-modal').html(e);
        }
    });
});