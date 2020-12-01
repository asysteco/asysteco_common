<script>
    $(document).ready(function () {
        $('#loading').delay().fadeOut()
    });
    $('.btn-select').on('click', function(e) {
        $('#btn-response').html(''),
        $("#loading-msg").html("Cargando..."),
        $("#loading").show(),
        e.preventDefault(),
        enlace = $(this).attr('enlace'),
        $('#btn-response').load(enlace)
    });
    $('.btn-export').on('click', function(e) {
        $('#btn-response').html(''),
        e.preventDefault(),
        enlace = $(this).attr('enlace'),
        window.open(enlace)
    });
    $('#backup').click(function () {
        $("#loading-msg").html("Preparando copia de seguridad..."),
        $('#loading').fadeIn(),
        setTimeout(() => {CheckBackupFile()}, 500);
    });

    function CheckBackupFile() {
        $.ajax({
            url: 'index.php?ACTION=clean_tmp',
            type: 'GET',
            data: {
                action: 'backup'
            },
            success: function (data) {
                if (data.match('deleted')) {
                    $("#loading").fadeOut();
                }
            },
            error: function (e) {
                $("#err").html(e).fadeIn();
            }
        });
    }
</script>