
<script>
$('.entrada').hide();

$(window).click(function() {
	$('.entrada').hide(),
	$('.txt').show()
});

$('.entrada, .txt').click(function(event){
    event.stopPropagation()
});

$('.txt').on('click', function(){
	texto=$(this).html(),
	datos=$(this).attr('id').split('_'),
	id=datos[1],
	columna=datos[2],
	$(this).hide();
	if(columna == 'Aula')
	{
		tx='#in_'+id+'_'+columna
	}
	else
	{
		tx='#in2_'+id+'_'+columna
	}
	$(tx).val(texto),
	$(tx).show().focus()
});

$('.entrada').on('change', function(){
	texto=$(this).val(),
	datos=$(this).attr('id').split('_'),
	id=datos[1],
	columna=datos[2],
	$(this).hide();
	if(columna == 'Aula')
	{
		sp='#sp_'+id+'_'+columna
	}
	else
	{
		sp='#sp2_'+id+'_'+columna
	}

   /*  if(! confirm('¿Modificar?'))
    {
        $(sp).show();
        return
    } */

	$(sp).html(texto),
	$(sp).show(),
	enlace="index.php?ACTION=horarios&OPT=update&SUBOPT=t-horario&id="+id+"&columna="+columna+"&texto="+texto,
	$('#response').load(encodeURI(enlace)),
	setTimeout(function(){location.reload()}, 500);
	
});
$('.act').on('click', function(event) {
	event.preventDefault(),
	enlace = $(this).attr('enlace'),
	$('#response').load(encodeURI(enlace)),
	setTimeout(function(){location.reload()}, 500);
});

var profesor = $('#profesor').attr('profesor');
$('.guardia').hide();
$('.guardia').on('click', function(){
enlace = $(this).attr('enlace'),
$('#response').load(enlace),
setTimeout(function(){location.href = location.href}, 500);
});

$('.remove-guardia').on('click', function(){
enlace = $(this).attr('enlace'),
$('#response').load(enlace),
setTimeout(function(){location.href = location.href}, 500);
});

$('.edificio').on('change', function() {
edificio = $(this).val(),
id = $(this).attr('id').split('-'),
plus = 'plus-'+id[1]+'-'+id[2];
if(edificio == '')
{
    $('#'+plus).hide();
    return
}
else
{
    enlace = $('#'+plus).attr('enlace'),
    $('#'+plus).attr('enlace', enlace+'&e='+edificio),
    $('#'+plus).show()
}
});
</script>