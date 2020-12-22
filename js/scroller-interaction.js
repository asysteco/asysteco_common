<script>
var scrollTime = 1000; // Tiempo de scroll
var wait = 9000; // Tiempo que tarda entre cada scroll

var whiteSpaceHeight = 21; // Altura del espaciado superior en px
var top = 'fila_0'; // Fila Inicial
var bottom = 'fila_16'; // Fila Final

function scrollDown() {
  $(".scroller").animate({
    scrollTop: $("#"+bottom).offset().top-whiteSpaceHeight
  }, scrollTime)
};

setInterval(() => {
  scrollDown();
}, wait);
</script>