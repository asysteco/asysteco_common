var enlace;

$(document).on('click', '.act', function(e) { 
    $('#modal-profesores').removeClass('modal-fs');   
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
        id = $(this).parent().attr('id').split('_'),
        id = id[1],
        enlace = 'index.php?ACTION=horarios&OPT=profesor&profesor=' + id;
        data = {};
    } else if (action === 'modal-asistencias') {
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=asistencias&ID=' + id;
        data = {};
    } else if (action === 'modal-desactivar') {
        e.preventDefault();
        cabecera = "<h5 class='modal-title'>Desactivar Profesor</h5>";
        contenido = "<i>¿Seguro que desea realizar este cambio? Utilice solo esta opción si el profesor deja el centro por motivos de jubilación, fin de una sustitución o similares.</i>";
        input = "<br><br><input id='fecha-desactivar' class='form-control' name='fecha' type='text' placeholder='Seleccione una fecha...' autocomplete='off'>";
        btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>";
        btn2 = "<button type='button' class='btn btn-success act float-right' action='desactivar'>Confirmar</button>";
        enlace = $(this).attr('enlace');
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
        cabecera = "<h5 class='modal-title'>Activar Profesor</h5>";
        contenido = "<i>¡Cuidado! Si realiza este cambio ahora, se considerará que el profesor vuelve a trabajar en el centro.</i>";
        btn1 = "<button type='button' class='btn btn-danger float-left' data-dismiss='modal'>Cancelar</button>"; 
        btn2 = "<button type='button' class='btn btn-success act float-right' action='activar'>Confirmar</button>";
        enlace = $(this).attr('enlace');
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
            loadingOn();
        },
        success: function (data) {
            if (action === 'horario') {
                $('#modal-size').addClass('modal-lg');
                $('#modal-contenido').html(data);
                $('#modal-pie').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
                $('#modal-profesores').modal('show')
                loadingOff();
                return;
            } else if (action === 'modal-asistencias') {
                $('#modal-profesores').addClass('modal-fs');
                $('#modal-contenido').html(data);
                $('#modal-pie').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
                $('#modal-profesores').modal('show')
                loadingOff();
                return;
            }

            if (data.match('activado')) {
                toastr["success"]("Profesor activado correctamente.", "Correcto!");
                setTimeout(function () { location.reload() }, 700);
            } else if (data.match('desact')) {
                toastr["success"]("Profesor desactivado correctamente.", "Correcto!");
                setTimeout(function () { location.reload() }, 700);
            } else if (data.match('error-activar')) {
                toastr["error"]("Error al activar profesor.", "Error!")
            } else if (data.match('error-desactivar')) {
                toastr["error"]("Error al desactivar profesor.", "Error!")
            } else if (data.match('error-fecha')) {
                toastr["error"]("Error en el formato de fecha.", "Error!")
            } else {
                toastr["error"]("Error inesperado...", "Error!")
            }
            loadingOff();
        },
        error: function (e) {
            $('#error-modal').modal('show'),
            $('#error-content-modal').html(e);
        }
    });
});