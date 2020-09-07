
<script>
$(function (){
    $('#fecha-edit').datepicker({ minDate: 0})
});
$('#fecha-edit').keypress(function(e) {
    e.preventDefault()
});
var href = $('#editar-horario').attr('href');
$('#fecha-edit').on('change', function() {
    fecha = $(this).val(),
    $('#editar-horario').attr('href', href+'&fecha='+fecha)
});
$('#editar-horario').on('click', function(event) {
    if($('#fecha-edit').val() == '')
    {
        alert('Debe seleccionar una fecha...'),
        $('#fecha-edit').focus(),
        event.preventDefault()
    }
});
</script>