
$(document).ready(function() {
    $("#frmCSVImport").on("submit", function () {
	    $("#response").attr("class", ""),
        $("#response").html(""),
        $("#userTable").remove(""),
        $("#loading-msg").html("Importando horarios con fecha: "+fecha+" ..."),
        $("#loading").show();
        var fileType = ".csv";
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#file").val().toLowerCase())) {
        	    $("#response").addClass("error"),
        	    $("#response").addClass("display-block"),
                $("#response").html("Tipo de fichero no válido. Documento válido: <b>" + fileType + "</b>.");
                return false;
        }
        return true;
    });
});

$(document).ready(function () {
    $('#loading').delay().fadeOut()
});
$('#btn-todos-registros-prof').on('click', function() {
    $("#todos-registros").html(""),
    $("#loading-msg").html("Cargando..."),
    $("#loading").show(),
    $('#todos-registros').load('index.php?ACTION=profesores&OPT=registros'),
    $("#loading").delay().fadeOut()
});

$(window).on('beforeunload', function(){
    return ;
});