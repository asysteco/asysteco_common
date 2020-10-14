<script>
var timeout = 1000;
var scrollTime = 10000;
var wait = 12000;
var again = 22000;

var top = 'fila_0';
var bottom = 'fila_25';

var action = function() {
  scrollDown();
  setTimeout(scrollUp, wait);
  setTimeout(action, again);
};

function scrollUp() {
  $(".scroller").animate({
    scrollTop: $("#"+top).offset().top
  }, scrollTime)
};

function scrollDown() {
  $(".scroller").animate({
    scrollTop: $("#"+bottom).offset().top
  }, scrollTime)
};

action();
</script>