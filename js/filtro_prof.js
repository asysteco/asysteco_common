$(function(){
  $('#busca_prof').keyup(function(){
    var val = $(this).val().toLowerCase();

    $(".row_prof").hide();

    $(".row_prof").each(function(){

      var text = $(this).text().toLowerCase();

      if(text.indexOf(val) != -1)
      {
        $(this).show();
      }
    });
  });
});