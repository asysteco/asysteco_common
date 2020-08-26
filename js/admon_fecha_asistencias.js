<script>
$('#fechainicioasis, #fechafinasis').keypress(function(e) {
    e.preventDefault()
});
var hrefasis = $('#fechaasiste').attr('enlace');
var fechaasis = $('#fechainicioasis').attr('enlace');
var fecha2asis = $('#fechafinasis').attr('enlace');
$('#fechainicioasis').on('change', function() {
    fechaasis = $(this).val(),
    $(function (){
        $('#fechafinasis').datepicker().focus();
    });
});
$('#fechafinasis').on('change', function() {
    fecha2asis = $(this).val(),
    $('#fechaasiste').attr('enlace', hrefasis+'&fechainicioasis='+fechaasis+'&fechafinasis='+fecha2asis).click()
});
$('#fechaasiste').on('click', function(event) {
    if($('#fechainicioasis').val() == '')
    {
        $(function (){
            $('#fechainicioasis').datepicker().focus();
        });
    }
});
</script>