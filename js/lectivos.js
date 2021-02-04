$('#datepicker_ini, #datepicker_fin, #datepicker_ini_fest, #datepicker_fin_fest').keypress(function(e) {
    e.preventDefault()
});
$(document).on('click', '.lectivo', function (e) {
    $('#modal-calendario').modal('show');
});