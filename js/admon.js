$('#backup').click(function () {
    loadingOn("Preparando copia de seguridad...");
    setTimeout(() => {CheckBackupFile()}, 500);
});

function CheckBackupFile(element = '') {
    if (element !== '') {
        data = {
            action: 'export',
            element: element
        };
    } else {
        data = {
            action: 'backup'
        };
    }

    $.ajax({
        url: 'index.php?ACTION=clean_tmp',
        type: 'GET',
        data: data,
        success: function (data) {
            if (data.match('deleted')) {
                loadingOff();
            }
        },
        error: function (e) {
            $("#err").html(e).fadeIn();
        }
    });
}
