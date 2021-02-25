$('#datepicker_ini, #datepicker_fin, #datepicker_ini_fest, #datepicker_fin_fest').keypress(function(e) {
    e.preventDefault()
});

let date;
let clicked;
let change;

$(document).on('click', '.act', function () {
    if($(this).attr('action')) {
        action = $(this).attr('action');
        enlace = 'index.php?ACTION=lectivos&OPT=alternar';

        if (action === 'lectivo') {
            changeName = 'festivo';
            msg = '¿Desea cambiar este día, actualmente calificado como día lectivo, a un día festivo?';
        } else if (action === 'festivo') {
            changeName = 'lectivo';
            msg = 'Actualmente este día es festivo, ¿Desea cambiarlo a lectivo?';
        } else if (action === 'set-lectivo') {
            change = 'lectivo';
            data = {
                action: 'no',
                date: date
            };

        } else if (action === 'set-festivo') {
            change = 'festivo';
            data = {
                action: 'si',
                date: date
            };

        } else {
            toastr["error"]("Acción desconocida...", "Error!")
            return;
        }

        if (action === 'lectivo' || action === 'festivo') {
            date = $(this).attr('data-date');
            clicked = $(this);
            btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>";
            btn2 = "<button type='button' class='act btn btn-success act float-right' action='set-" + changeName + "'>Confirmar</button>";
            $('#modal-cabecera').html('<h5>Cambiar a ' + changeName + ' </h5>');
            $('#modal-cabecera').append('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>');
            $('#modal-contenido').html('<i>' + msg + '</i>');
            $('#modal-pie').html(btn1);
            $('#modal-pie').append(btn2);
            $('#modal-pie').attr('class', 'modal-buttons-footer');
            $('#modal-calendario').modal('show');
        }
        
        if (action === 'set-lectivo' || action === 'set-festivo') {
            $.ajax({
                url: enlace,
                type: "POST",
                data: data,
                beforeSend: function () {
                    $('#modal-calendario').modal('hide');
                    loadingOn();
                },
                success: function (data) {
                    if(data.match('^cambio-realizado$')) {
                        toastr["success"]("Cambios realizados correctamente.", "Correcto!");
                        $(clicked).toggleClass('festivo');
                        $(clicked).attr('action', change);
                    } else if (data.match('^datos-incorrectos$')) {
                        toastr["error"]("Los datos son incorrectos.", "Error!");
                    } else {
                        toastr["error"]("Error inesperado...", "Error!");
                    }
                    loadingOff();
                },
                error: function (e) {
                    toastr["error"]("Error inesperado...", "Error!");
                }
            });
        }
    }
});
