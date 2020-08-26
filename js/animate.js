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
      $("#admon-icon").addClass('glyphicon-folder-open')
    }, 
    function () {
      $("#admon-icon").removeClass('glyphicon-folder-open')
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
</script>