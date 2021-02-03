$('#register-form').on('submit', function(e){
    e.preventDefault();
    data =$('#register-form').serialize();
    urlPath = 'index.php?ACTION=profesores&OPT=register-profesor';
    iniciales = $('#iniciales').val();
    nombre = $('#nombre').val();
    
    if (iniciales.length < 2 || nombre.length < 2) {
        toastr['warning']("Debe rellenar todos los campos.", "Advertencia!");
        return;
    }

    $.ajax({
        url: urlPath,
        type: "POST",
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (data) {
            if (data.match('^Registrado$')) {
                toastr["success"]("Personal Añadido");
                setTimeout(function () { location.reload() }, 700);
            } else if(data.match('^Nombre-Incorrecto$')) {
                toastr["error"]("Formato de Nombre incorrecto.", "Error!");
            } else if (data.match('^Iniciales-Incorrecto$')) {
                toastr["error"]("Formato de iniciales incorrecto.", "Error!");
            } else if (data.match('^Duplicado$')) {
                toastr["error"]("No se pueden duplicar las iniciales.", "Error!");
            } else if (data.match('^Error-query$')){
                toastr["error"]("Error al registrar personal.", "Error!");
            } else {
                toastr["error"]("Error inesperado...", "Error!");
            }
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!");
        }
    });
});
