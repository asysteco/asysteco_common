<script>
$(document).ready(function(){
    message = $('#message_plus').html(),
    $('#mostrar').hide();
    $("#show").click(function(){
        $('.new_mess').toggleClass('new_mess_close'),
        $('#mostrar').toggle('400'),
        atributo = $('#listado_mensajes').attr('class');
        if(atributo == 'col-xs-12')
        {
			$('#listado_mensajes').toggleClass('col-md-8'),
            $('#message_plus').html('Cerrar mensaje')
        }
        else
        {
			$('#listado_mensajes').delay('350').toggleClass('col-md-8', 100),
            $('#message_plus').html(message)
        }
     })
});
</script>