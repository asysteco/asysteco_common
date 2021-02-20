$('#datepicker_ini, #datepicker_fin, #datepicker_ini_fest, #datepicker_fin_fest').keypress(function(e) {
    e.preventDefault()
});
$(document).on('click', '.lectivo', function (e) {
    e.preventDefault();
    btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>";
    btn2 = "<button type='button' class='btn btn-success act float-right' action='change-lectivo'>Confirmar</button>";
    $('#modal-cabecera').html('<h5>Cambiar a festivo</h5>');
    $('#modal-cabecera').append('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>');
    $('#modal-contenido').html('<i>¿Desea cambiar este día, actualmente calificado como día lectivo, a un día festivo?</i>');
    $('#modal-pie').html(btn1);
    $('#modal-pie').append(btn2);
    $('#modal-pie').attr('class', 'modal-buttons-footer');
    $('#modal-calendario').modal('show');
});


$(document).on('click', '.festivo', function (e) {
    e.preventDefault();
    btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>";
    btn2 = "<button type='button' class='btn btn-success act float-right' action='change-festivo'>Confirmar</button>";
    $('#modal-cabecera').html('<h5>Cambiar a lectivo</h5>');
    $('#modal-cabecera').append('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>');
    $('#modal-contenido').html('<i>Actualmente este día es festivo, ¿Desea cambiarlo a lectivo?</i>');
    $('#modal-pie').html(btn1);
    $('#modal-pie').append(btn2);
    $('#modal-pie').attr('class', 'modal-buttons-footer');
    $('#modal-calendario').modal('show');
});

