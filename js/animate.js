<script>
$("#message").hover(
    function () {
      $("#message-icon").addClass('message-jump')
    }, 
    function () {
      $("#message-icon").removeClass('message-jump')
    }
);
$("#admon").hover(
    function () {
      $("#admon-icon").addClass('fa-folder-open-o')
    }, 
    function () {
      $("#admon-icon").removeClass('fa-folder-open-o')
    }
);
$("#notif").hover(
    function () {
      $("#notif-icon").addClass('bell_rings')
    }, 
    function () {
      $("#notif-icon").removeClass('bell_rings')
    }
);
$("#cambio-pass").hover(
    function () {
      $("#cambio-pass-icon").addClass('rotate-pass')
    }, 
    function () {
      $("#cambio-pass-icon").removeClass('rotate-pass')
    }
);
$("#info-horario").hover(
    function () {
      $("#info-horario-icon").addClass('fa-calendar-check-o')
    }, 
    function () {
      $("#info-horario-icon").removeClass('fa-calendar-check-o')
    }
);
</script>