$(document).ready(function (e) {
    $("#loading").hide();
    $("#frmCSVImport").on('submit',(function(e) {
     e.preventDefault();
     url = $(this).attr('action');
     opt = 'preview';
     urlPath = url+opt;
     usedMethod = $(this).attr('method');
     $.ajax({
      url: urlPath,
      type: usedMethod,
      data:  new FormData(this),
      contentType: false,
      cache: false,
      processData:false,
      beforeSend : function()
      {
        $("#loading-msg").html("Importando horarios...");
        $("#loading").show();
        $("#err").fadeOut();
      },
      success: function(data) {
        if (data.match('error-cabecera')) {
          alert('Error de cabecera, comprueba el formato del fichero.')
        } else {
          $('#file-content-modal').modal('show'),
          $('#file-content-preview').html(data)
        }
          $("#loading").fadeOut();
         },
        error: function(e) {
            $("#err").html(e).fadeIn();
         }          
       });
    }));

    $('.import-data').on('click', function() {
        form = $("#frmCSVImport");
        url = $(form).attr('action');
        opt = 'import-csv';
        urlPath = url+opt;
        usedMethod = $(form).attr('method');
        $.ajax({
         url: urlPath,
         type: usedMethod,
         data:  new FormData($("#frmCSVImport")[0]),
         contentType: false,
         cache: false,
         processData:false,
         beforeSend : function() {
           $('#file-content-modal').modal('hide'),
           $("#loading-msg").html("Importando horarios...");
           $("#loading").show();
           $("#err").fadeOut();
         },
         success: function(data) {
          if (data.match('Error-importar')) {
            alert('Error al importar fichero.')
          } else {
            $('#file-content-modal').modal('show'),
            $('#file-content-preview').html(data)
          }
             $("#loading").fadeOut();
             //location.reload();
            },
           error: function(e) {
               $("#err").html(e).fadeIn();
            }          
          });
    });
   });