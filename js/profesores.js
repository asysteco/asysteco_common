var enlace;
var id;
var nombre;

$(document).on('click', '.act', function(e) { 
    $('#modal-profesores').removeClass('modal-fs');
    $('#modal-size').removeClass('modal-fs');
    $('#modal-cabecera').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>');
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
        id = $(this).attr('profesor');
        nombre = $(this).attr('nombre');
        enlace = 'index.php?ACTION=horarios&OPT=profesor';
        data = {profesor: id};
    } else if (action === 'remove-horario') {
        confirmed = confirm('¿Seguro que desea eliminar el horario de este profesor?');
        if (!confirmed) {
            $('#modal-profesores').addClass('modal-fs');
            return;
        }
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=horarios&OPT=remove';
        data = {profesor: id};
    } else if (action === 'modal-form-clonar') {
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=horarios&OPT=clonar';
        data = {profesor: id};
    } else if (action === 'clonar-horario') {
        idClonado = $('#select_clonado').val();
        nombreClonado = $( "#select_clonado option:selected" ).text();
        confirmed = confirm('¿Seguro que desea clonar el horario de ' + nombre + ' a ' + nombreClonado + '?');
        $('#modal-pie').attr('class', 'modal-buttons-footer');
        if (!confirmed) {
            return;
        }
        enlace = 'index.php?ACTION=horarios&OPT=clonar';
        data = {
            subOpt: 'Ajax',
            'ID_PROFESOR': id,
            'ID_CLONADO': idClonado
        }
    } else if (action === 'reset') {
        id = $(this).attr('profesor');
        data = {};
    } else if (action === 'modal-asistencias') {
        id = $(this).attr('profesor');
        nombre = $(this).attr('nombre');
        enlace = 'index.php?ACTION=asistencias&ID=' + id;
        data = {};
    } else if (action === 'actualizar-profesor') {
        enlace = 'index.php?ACTION=profesores&OPT=actualizar';
        data = $('#formulario-edit').serialize();
    } else if (action === 'modal-editar') {
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=profesores&OPT=edit&ID=' + id;
        data = {};
    } else if (action === 'modal-form-sustituir') {
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=profesores&OPT=sustituir&ID=' + id;
        data = {};
    } else if (action === 'realizar-sustitucion') {
        idSustituto = $('#select_sustituto').val();
        enlace = 'index.php?ACTION=profesores&OPT=add-sustituto';
        data = {
            'ID_PROFESOR': id,
            'ID_SUSTITUTO': idSustituto
        };
    } else if (action === 'modal-fin-sustitucion') {
        id = $(this).attr('profesor');
        enlace = 'index.php?ACTION=profesores&OPT=remove-sustituto&ID=' + id;
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
                $('#modal-cabecera').html('<h5>Faltas y Asistencias de ' + nombre + ' </h5>');
                $('#modal-cabecera').append('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>');
                $('#modal-contenido').html(data);
                $('#modal-pie').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');
                $('#busca_asiste').datepicker({
                    beforeShowDay: $.datepicker.noWeekends
                });
                $('#modal-profesores').modal('show')
                loadingOff();
                return;
            } else if (action === 'modal-editar') {
                $('#modal-size').addClass('modal-lg');
                $('#modal-contenido').html(data);
                $('#modal-pie').html('<button type="button" class="btn btn-danger float-left" data-dismiss="modal">Cancelar</button>');
                $('#modal-pie').append('<button class="btn btn-success act float-right" action="actualizar-profesor" name="ACTION" value="editar_profesor">Actualizar</button></br></br>');
                $('#modal-pie').attr('class', 'modal-buttons-footer');
                $('#modal-profesores').modal('show')
                loadingOff();
                return;
            } else if (action === 'modal-form-sustituir') {
                $('#modal-contenido').html(data);
                $('#modal-pie').html('<button type="button" class="btn btn-danger float-left" data-dismiss="modal">Cancelar</button>');
                $('#modal-pie').append('<button class="btn btn-success float-right act" value="profesores" name="ACTION" action="realizar-sustitucion">Agregar</button>');
                $('#modal-pie').attr('class', 'modal-buttons-footer');
                $('#modal-profesores').modal('show')
                loadingOff();
                return;
            } else if (action === 'modal-form-clonar') {
                $('#modal-contenido').html(data);
                $('#modal-pie').html('<button type="button" class="btn btn-danger float-left" data-dismiss="modal">Cancelar</button>');
                $('#modal-pie').append('<button class="btn btn-success float-right act" value="profesores" name="ACTION" action="clonar-horario">Confirmar</button>');
                $('#modal-pie').attr('class', 'modal-buttons-footer');
                $('#modal-profesores').modal('show')
                loadingOff();
                return;
            }
            response = JSON.parse(data);
            
            if (response.success) {
                toastr["success"](response.msg, "Correcto!");
                if (response.reload) {
                    setTimeout(() => { location.reload() }, 700);
                    return;
                }
            } else {
                toastr["error"](response.msg, "Error!");
            }
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!")
        }
    });
});