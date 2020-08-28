
<script>
$('.entrada').hide();

$(window).click(function() {
	$('.entrada').hide(),
	$('#grupo-tutor').show()
});

$('.entrada').click(function(event){
    event.stopPropagation()
});

$('#grupo-tutor').on('click', function(event){
    event.stopPropagation(),
    texto=$(this).val(),
	$(this).hide(),
	$('#grupo-tutor-select').val(texto),
	$('#grupo-tutor-select').show().focus()
});

$('.entrada').on('change', function(){
	texto=$(this).val(),
	$(this).hide(),
	$('#grupo-tutor').val(texto),
	$('#grupo-tutor').show()
});

</script>