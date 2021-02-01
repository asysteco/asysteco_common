$('#register-form').on('submit', function(e){
    e.preventDefault();
    data =$('#register-form').serialize();
    urlPath = 'index.php?ACTION=profesores&OPT=add-profesor';

    $.ajax({
        url: urlPath,
        type: "POST",
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (data) {
            if (data.match('Ok-action')) {
                toastr["success"]("Registrado Profesor/Personal Correctamente.")
                setTimeout(function () { location.reload() }, 700)
            }
              else if(data.match('Nombre-Incorrecto')) {
                toastr["error"]("Formato de Nombre incorrecto.", "Error")
            } else if (data.match('Iniciales-Incorrecto')) {
                toastr["error"]("Formato de iniciales incorrecto.", "Error")
            } else if (data.match('Duplicado')) {
                toastr["error"]("No se pueden duplicar las iniciales.", "Error")
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
