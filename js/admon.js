<script>
    $(document).ready(function () {
        $('#loading').delay().fadeOut()
    });
    $('.btn-select').on('click', function(event) {
        $('#btn-response').html(''),
        $("#loading-msg").html("Cargando..."),
        $("#loading").show(),
        event.preventDefault(),
        enlace = $(this).attr('enlace'),
        $('#btn-response').load(enlace)
    });
    $('.btn-export').on('click', function(event) {
        $('#btn-response').html(''),
        event.preventDefault(),
        enlace = $(this).attr('enlace'),
        window.open(enlace)
    });
</script>