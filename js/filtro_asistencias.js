$(function(){
  $(document).on('keyup change', '#busca_asiste', function(){
    var val = $(this).val().toLowerCase();

    $("#table-asistencias tbody tr, #table-fichajes tbody tr").hide();

    $("#table-asistencias tbody tr, #table-fichajes tbody tr").each(function(){

      var text = $(this).text().toLowerCase();

      if(text.indexOf(val) != -1)
      {
        $(this).show();
      }
    });
  });
});