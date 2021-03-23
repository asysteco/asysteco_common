$('#file').on('change',function(e){
    if (e.target.files.length > 0) {
        var fileName = e.target.files[0].name;
        $('#fileName').html(fileName);
        $('#submit').prop("disabled", false);
    } else {
        $('#fileName').html('Subir CSV');
        $('#submit').prop("disabled", true);
    }
});
$('#toggleInfo').on('click', function() {
    $('#info-formato-body').html($('#ayuda-formato').html());
    $('#info-formato-modal').modal('show');
});
