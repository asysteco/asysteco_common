$(document).on('submit', function(e) {
    e.preventDefault();
    data = $('#change-pass').serialize();
    urlPath = $('#change-pass').attr('action');
    redirect = 'index.php';

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
                setTimeout(() => { location.href = redirect }, 700);
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