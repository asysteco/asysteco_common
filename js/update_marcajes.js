<script>
    $('.actualiza').on('click', function () {
        datos = $(this).attr('asiste').split(','),
        Profesor = datos[0],
        Fecha = datos[1],
        Hora = datos[2],
        Valor = datos[3];
        if($('#marcaje-response').load('index.php?ACTION=marcajes&OPT=update&Profesor='+Profesor+'&Fecha='+Fecha+'&Hora='+Hora+'&Valor='+Valor))
        {
            $('#table-container').hide(),
            setTimeout(function(){location.reload()}, 200)
        }
        else
        {
            $('#marcaje-response').html('Error')
        }
    });
</script>