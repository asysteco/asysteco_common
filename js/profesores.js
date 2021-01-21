$('.row-show').on('click', function() {
    console.log('pulsado');
    id = $(this).parent().attr('id').split('_'),
    id = id[1],
    $("#horario").load('index.php?ACTION=horarios&OPT=profesor&profesor=' + id),
    $('#modal-horario').modal('show')
});