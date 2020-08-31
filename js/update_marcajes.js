<script>
    $('.actualiza').on('click', function () {
        datos = $(this).attr('asiste').split(','),
        Profesor = datos[0],
        Fecha = datos[1],
        Hora = datos[2],
        Valor = datos[3];
        if($('#marcaje-response').load('index.php?ACTION=update_marcajes&Profesor='+Profesor+'&Fecha='+Fecha+'&Hora='+Hora+'&Valor='+Valor))
        {
            $('#table-container').load('index.php?ACTION=asistencias&ID='+Profesor+' #full-table'),
            location.reload()
        }
        else
        {
            $('#marcaje-response').html('Error')
        }
    });
</script>