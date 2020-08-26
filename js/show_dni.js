<script>

window.onload = function() {
    $('.oculta').hide()
  };
$( ".row_show" ).mouseenter(function() {
    $( ".muestra", this ).hide(),
    $( ".oculta", this ).show()
}).mouseleave(function() {
    $( ".oculta", this ).hide(),
    $( ".muestra", this ).show()
});
</script>