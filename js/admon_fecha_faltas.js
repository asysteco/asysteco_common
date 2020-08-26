<script>
$('#fechainifaltas, #fechafinfaltas').keypress(function(e) {
    e.preventDefault()
});
var hreffal = $('#fechafaltas').attr('enlace');
var fechafal = $('#fechainifaltas').attr('enlace');
var fecha2fal = $('#fechafinfaltas').attr('enlace');
$('#fechainifaltas').on('change', function() {
    fechafal = $(this).val(),
    $(function (){
        $('#fechafinfaltas').datepicker().focus();
    });
});
$('#fechafinfaltas').on('change', function() {
    fecha2fal = $(this).val(),
    $('#fechafaltas').attr('enlace', hreffal+'&fechainifaltas='+fechafal+'&fechafinfaltas='+fecha2fal).click()
});
$('#fechafaltas').on('click', function(event) {
    if($('#fechainifaltas').val() == '')
    {
        $(function (){
            $('#fechainifaltas').datepicker().focus();
        });
    }
});
</script>