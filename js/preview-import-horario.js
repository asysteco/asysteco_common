$(document).ready(function (e) {
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
        $("#err").fadeOut();
      },
      success: function (data) {
        if (data.match('error-cabecera')) {
          toastr["error"]("Error de cabecera, comprueba el formato del fichero.", "Error!")
        } else if (data.match('error-file')) {
          toastr["error"]("Error de fichero, puede que esté dañado", "Error!")
        } else if (data.match('error-edificio')) {
          toastr["error"]("El edificio especificado en el fichero CSV no es numérico", "Error!")
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
        $("#err").fadeOut();
      },
      success: function (data) {
        if (data.match('Error-importar')) {
          toastr["error"]("Error al importar fichero.", "Error!")
        } else if (data.match('No-profesor')) {
          toastr["error"]("El fichero CSV contiene iniciales que no hacen referencia a ningún profesor existente.", "Error!")
        } else if (data.match('Error-unexpected')) {
          toastr["error"]("Error inesperado, contacte con los administradores.", "Error!")
        } else if (data.match('empty-import')) {
          toastr["success"]("No hay nada nuevo para importar.", "Correcto!");
        } else if (data.match('error-edificio')) {
          toastr["error"]("El edificio especificado en el fichero CSV no es numérico", "Error!")
        } else {
          toastr["success"]("¡Datos importados con éxito!", "Correcto!");
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