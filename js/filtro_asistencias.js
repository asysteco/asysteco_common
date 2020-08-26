<script>
$(function(){
  $('#busca_asiste').on('keyup change', function(){
    var val = $(this).val().toLowerCase();

    $("tbody tr").hide();

    $("tbody tr").each(function(){

      var text = $(this).text().toLowerCase();

      if(text.indexOf(val) != -1)
      {
        $(this).show();
      }
    });
  });
});
</script>