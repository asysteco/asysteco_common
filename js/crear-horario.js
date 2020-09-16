
<script>
$(document).ready(function() {
	$('.table').show();
	$('.act').on('click', function(event) {
		event.preventDefault(),
		datos = $('#profesor').attr('profesor').split('_'),
		profesor = datos[0],
		fecha = datos[1],
		enlace = $(this).attr('enlace'),
		$('#response').load(encodeURI(enlace)),
		setTimeout(function(){location.href = "index.php?ACTION=horarios&OPT=edit-horario-profesor&profesor="+profesor+"&fecha="+fecha}, 500);
	});
	
	$('#select_tipo').on('change', function(event) {
		tipo = '&Tipo='+$(this).val(),
		spl = location.href.split('&'),
		enlace = spl[0]+'&'+spl[1]+'&'+spl[2]+tipo,
		window.location = enlace
	});
});
</script>