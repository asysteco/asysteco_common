$('#info-horario').on('click', function() {
    urlPath = "index.php?ACTION=horarios&OPT=info";
    $.ajax({
        url: urlPath,
        type: 'GET',
        data: {},
        beforeSend: function () {
            loadingOn();
        },
        success: function (data) {
            $('#info-horario-body').html(data);
            $('#info-horario-modal').modal('show');
            loadingOff();
        },
        error: function (e) {
            toastr["error"]("Error inesperado...", "Error!")
        }
    });
});