$(document).on('submit', function(e) {
    e.preventDefault();
    data = $('#first-change-pass').serialize();
    urlPath = $('#first-change-pass').attr('action');

    $.ajax({
        url: urlPath,
        type: 'POST',
        data: data,
        beforeSend: function () {
            loadingOn();
        },
        success: function (data) {
            response = JSON.parse(data);

            if (response.success) {
                toastr["success"](response.msg, "Correcto!");
                setTimeout(() => { location.reload() }, 700);
            } else {
                loadingOff();
                toastr["error"](response.msg, "Error!");
            }
        },
        error: function () {
            loadingOff();
            toastr["error"]("Error inesperado...", "Error!");
        }
    });
});