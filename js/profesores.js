var enlace;

$(document).on('click', '.act', function(e) { 
    $('#modal-profesores').removeClass('modal-fs');
    $('#modal-size').removeClass('modal-fs');
    $('#modal-cabecera').html('');
    $('#modal-pie').attr('class', 'modal-footer');

    action = $(this).attr('action');
    if (action === 'activar') {
        data = {
            action: action
        };
    } else if (action === 'desactivar') {
        fecha = $('#fecha-desactivar').val();
        if (fecha === '') {
            $('#fecha-desactivar').focus();
            toastr["warning"]("Debe seleccionar una fecha de desactivación.", "Advertencia!");
            return;
        }
        data = {
            action: action,
            fecha: fecha
        };
    } else if (action === 'horario') {
        id = $(this).parent().attr('id').split('_');
        id = id[1];
        enlace = 'index.php?ACTION=horarios&OPT=profesor&profesor=' + id;
        data = {};
    } else if (action === 'reset') {
        id = $(this).attr('profesor');
        data = {};
    } else if (action === 'modal-asistencias') {
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=asistencias&ID=' + id;
        data = {};
    } else if (action === 'modal-desactivar') {
        e.preventDefault();
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=profesores&OPT=des-act&ID=' + id;
        cabecera = "<h5 class='modal-title'>Desactivar Profesor</h5>";
        contenido = "<i>¿Seguro que desea realizar este cambio? Utilice solo esta opción si el profesor deja el centro por motivos de jubilación, fin de una sustitución o similares.</i>";
        input = "<br><br><input id='fecha-desactivar' class='form-control' name='fecha' type='text' placeholder='Seleccione una fecha...' autocomplete='off'>";
        btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>";
        btn2 = "<button type='button' class='btn btn-success act float-right' action='desactivar'>Confirmar</button>";
        $('#modal-cabecera').html(cabecera);
        $('#modal-contenido').html(contenido);
        $('#modal-contenido').append(input);
        $('#fecha-desactivar').datepicker({minDate: -5, maxDate: 0});
        $('#modal-pie').html(btn1);
        $('#modal-pie').append(btn2);
        $('#modal-pie').attr('class', 'modal-buttons-footer');
        $('#modal-profesores').modal('show');
        return;
    } else if (action === 'modal-activar') {
        e.preventDefault();
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=profesores&OPT=des-act&ID=' + id;
        cabecera = "<h5 class='modal-title'>Activar Profesor</h5>";
        contenido = "<i>¡Cuidado! Si realiza este cambio ahora, se considerará que el profesor vuelve a trabajar en el centro.</i>";
        btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>"; 
        btn2 = "<button type='button' class='btn btn-success act float-right' action='activar'>Confirmar</button>";
        $('#modal-cabecera').html(cabecera);
        $('#modal-contenido').html(contenido);
        $('#modal-pie').html(btn1);
        $('#modal-pie').append(btn2);
        $('#modal-pie').attr('class', 'modal-buttons-footer');
        $('#modal-profesores').modal('show');
        return;
    } else if (action === 'modal-reset') {
        e.preventDefault();
        cabecera = "<h5 class='modal-title'>Restablecer contraseña</h5>";
        nombre = $(this).attr('nombre');
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=profesores&OPT=reset-pass&ID=' + id;
        contenido = "<i>Va a restablecer la contraseña de <b>" + nombre + "</b> ¿Desea continuar?</i>";
        btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>"; 
        btn2 = "<button type='button' class='btn btn-success act float-right' action='reset'>Confirmar</button>";
        console.log(enlace);
        $('#modal-cabecera').html(cabecera);
        $('#modal-contenido').html(contenido);
        $('#modal-pie').html(btn1);
        $('#modal-pie').append(btn2);
        $('#modal-pie').attr('class', 'modal-buttons-footer');
        $('#modal-profesores').modal('show');
        return;
    } else {
        toastr["error"]("Acción no permitida.", "Error!");
        return;
    }
    $.ajax({
        url: enlace,
        type: "POST",
        data: data,
        beforeSend: function () {
            $('#modal-profesores').modal('hide');
            loadingOn();
        },
        success: function (data) {
            if (action === 'horario') {
                if (!data.match('no tiene horario')) {
                    $('#modal-profesores').addClass('modal-fs');
                }
                $('#modal-contenido').html(data);
                $('#modal-pie').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
                $('#modal-profesores').modal('show')
                loadingOff();
                return;
            } else if (action === 'modal-asistencias') {
                $('#modal-profesores').addClass('modal-fs');
                $('#modal-contenido').html(data);
                $('#modal-pie').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
                $('#busca_asiste').datepicker({
                    beforeShowDay: $.datepicker.noWeekends
                });
                $('#modal-profesores').modal('show')
                loadingOff();
                return;
            }

            if (data.match('^activado$')) {
                toastr["success"]("Profesor activado correctamente.", "Correcto!");
                setTimeout(function () { location.reload() }, 700);
            } else if (data.match('^desactivado$')) {
                toastr["success"]("Profesor desactivado correctamente.", "Correcto!");
                setTimeout(function () { location.reload() }, 700);
            } else if (data.match('^error-activar$')) {
                toastr["error"]("Error al activar profesor.", "Error!");
            } else if (data.match('^error-desactivar$')) {
                toastr["error"]("Error al desactivar profesor.", "Error!");
            } else if (data.match('^error-fecha$')) {
                toastr["error"]("Error en el formato de fecha.", "Error!");
            } else if (data.match('^error-reset$')) {
                toastr["error"]("No se ha podido restablecer la contraseña.", "Error!");
            } else if (data.match('^ok-reset$')) {
                toastr["success"]("Se ha restablecido la contraseña correctamente.", "Correcto!");
            } else {
                toastr["error"]("Error inesperado...", "Error!")
            }
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!")
        }
    });
});