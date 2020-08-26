<script>
$(document).ready(function(){
    message = $('#message_plus').html(),
    $('#mostrar').hide();
    $("#show").click(function(){
        $('.new_mess').toggleClass('new_mess_close'),
        $('#mostrar').toggle(),
        atributo = $('#listado_mensajes').attr('class');
        if(atributo == 'col-xs-12')
        {
            $('#listado_mensajes').attr('class', atributo+' col-md-8'),
            $('#message_plus').html('Cerrar mensaje')
        }
        else
        {
            $('#listado_mensajes').attr('class', 'col-xs-12'),
            $('#message_plus').html(message)
        }
     })
});
</script>