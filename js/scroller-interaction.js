<script>
var scrollTime = 1000; // Tiempo de scroll
var wait = 9000; // Tiempo que tarda entre cada scroll

var whiteSpaceHeight = 21; // Altura del espaciado superior en px
var topRow = 'fila_0'; // Fila Inicial
var bottomRow = 'fila_16'; // Fila Final

function scrollDown() {
  $(".scroller").animate({
    scrollTop: $("#"+bottomRow).offset().topRow-whiteSpaceHeight
  }, scrollTime)
};

setInterval(() => {
  if ($('#'+topRow).length && $('#'+bottomRow).length) {
    scrollDown();
  }
}, wait);
</script>