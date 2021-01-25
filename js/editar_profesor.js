$(window).click(function() {
	$('.entrada').hide(),
	$('#grupo-tutor').show()
});

$(document).on('click', '.entrada', function(event){
    event.stopPropagation()
});

$(document).on('click', '#grupo-tutor', function(event){
    event.stopPropagation(),
    texto=$(this).val(),
	$(this).hide(),
	$('#grupo-tutor-select').val(texto),
	$('#grupo-tutor-select').show().focus()
});

$(document).on('change', '.entrada', function(){
	texto=$(this).val(),
	$(this).hide(),
	$('#grupo-tutor').val(texto),
	$('#grupo-tutor').show()
});