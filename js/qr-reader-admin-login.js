<script>
    $(document).ready( function () {
        $('#QR-lector').focus()
    });
    var val;
    $('#QR-form').submit(function (e) {
        e.preventDefault(),
        val = $('#QR-lector').val(),
        $('#QR-lector').val(''),
        $('#output').load('index.php?ACTION=admin-login&criptedval='+encodeURI(val)),
        setTimeout(function(){
            $('#output').html("<span id='empty'><h3>Acerque el código QR al lector...</h3></span>"),
            $('#QR-lector').focus()
        }, 1500);
    });
</script>