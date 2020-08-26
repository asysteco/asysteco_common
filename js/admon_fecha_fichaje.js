<script>
$('#fechainifichaje, #fechafinfichaje').keypress(function(e) {
    e.preventDefault()
});
var href = $('#fechafichajes').attr('enlace');
var fecha = $('#fechainifichaje').attr('enlace');
var fecha2 = $('#fechafinfichaje').attr('enlace');
$('#fechainifichaje').on('change', function() {
    fecha = $(this).val(),
    $(function (){
        $('#fechafinfichaje').datepicker().focus();
    });
});
$('#fechafinfichaje').on('change', function() {
    fecha2 = $(this).val(),
    $('#fechafichajes').attr('enlace', href+'&fechainifichaje='+fecha+'&fechafinfichaje='+fecha2).click()
});
$('#fechafichajes').on('click', function(event) {
    if($('#fechainifichaje').val() == '')
    {
        $(function (){
            $('#fichafeini').datepicker().focus();
        });
    }
});
</script>