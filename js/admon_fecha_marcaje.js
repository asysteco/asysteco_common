<script>
$('#fechainimarc, #fechafinmarc').keypress(function(e) {
    e.preventDefault()
});
var hrefmarc = $('#fechamarcajes').attr('enlace');
var fechamarc = $('#fechainimarc').attr('enlace');
var fecha2marc = $('#fechafinmarc').attr('enlace');
$('#fechainimarc').on('change', function() {
    fechamarc = $(this).val(),
    $(function (){
        $('#fechafinmarc').datepicker().focus();
    });
});
$('#fechafinmarc').on('change', function() {
    fecha2marc = $(this).val(),
    $('#fechamarcajes').attr('enlace', hrefmarc+'&fechainimarc='+fechamarc+'&fechafinmarc='+fecha2marc).click()
});
$('#fechamarcajes').on('click', function(event) {
    if($('#fechainimarc').val() == '')
    {
        $(function (){
            $('#fechainimarc').datepicker().focus();
        });
    }
});
</script>