$(document).ready(function (e) {
  $("#loading").hide();
  $("#frmCSVImport").on('submit', (function (e) {
    e.preventDefault();
    url = $(this).attr('action');
    opt = 'preview';
    urlPath = url + opt;
    usedMethod = $(this).attr('method');
    $.ajax({
      url: urlPath,
      type: usedMethod,
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $("#loading-msg").html("Cargando horarios CSV...");
        $("#loading").show();
        $("#loading").css('z-index', 99);
        $("#err").fadeOut();
      },
      success: function (data) {
        if (data.match('error-cabecera')) {
          $('#error-modal').modal('show'),
            $('#error-content-modal').html('<h3>Error de cabecera, comprueba el formato del fichero.</h3>')
        } else if (data.match('error-file')) {
          $('#error-modal').modal('show'),
            $('#error-content-modal').html('<h3>Error de fichero, puede que esté dañado</h3>')
        } else {
          $('#file-content-modal').modal('show'),
            $('#file-content-preview').html(data)
        }
        $("#loading").fadeOut();
      },
      error: function (e) {
        $("#err").html(e).fadeIn();
      }
    });
  }));

  $('.import-data').on('click', function () {
    form = $("#frmCSVImport");
    url = $(form).attr('action');
    opt = 'import-csv';
    urlPath = url + opt;
    usedMethod = $(form).attr('method');
    $.ajax({
      url: urlPath,
      type: usedMethod,
      data: new FormData($("#frmCSVImport")[0]),
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $('#file-content-modal').modal('hide'),
          $("#loading-msg").html("Importando horarios...");
        $("#loading").show();
        $("#loading").css('z-index', 99);
        $("#err").fadeOut();
      },
      success: function (data) {
        if (data.match('Error-importar')) {
          $('#error-modal').modal('show'),
            $('#error-content-modal').html('<h3>Error al importar fichero.</h3>')
        } else if (data.match('No-profesor')) {
          $('#error-modal').modal('show'),
            $('#error-content-modal').html('<h3>El fichero CSV contiene iniciales que no hacen referencia a ningún profesor existente.</h3>')
        } else {
          $('#fine-modal').modal('show'),
            $('#fine-content-modal').html('<h3>¡Datos importados con éxito!</h3>');
          setTimeout(function () { location.reload() }, 700);
        }
        $("#loading").fadeOut();
      },
      error: function (e) {
        $("#err").html(e).fadeIn();
      }
    });
  });
});