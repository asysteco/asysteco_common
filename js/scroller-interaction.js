<script>
var scrollTime = 1000; // Tiempo de scroll
var wait = 9000; // Tiempo que tarda entre cada scroll

var top = 'fila_0'; // Fila Inicial
var bottom = 'fila_15'; // Fila Final

function scrollDown() {
  $(".scroller").animate({
    scrollTop: $("#"+bottom).offset().top
  }, scrollTime)
};

setInterval(() => {
  scrollDown();
}, wait);
</script>