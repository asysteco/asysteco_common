var modal = '<div id="info-horario-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">';
    modal += '<div class="modal-dialog modal-lg">';
        modal += '<div class="modal-content">';
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

$('#file').on('change',function(e){
    if (e.target.files.length > 0) {
        var fileName = e.target.files[0].name;
        $('#fileName').html(fileName);
        $('#submit').prop("disabled", false);
    } else {
        $('#fileName').html('Subir CSV');
        $('#submit').prop("disabled", true);
    }
});
$('#toggleInfo').on('click', function() {
    $('body').append(modal);
    $('#info-horario-body').html($('#ayuda-formato').html());
    $('#info-horario-modal').modal('show');
});
