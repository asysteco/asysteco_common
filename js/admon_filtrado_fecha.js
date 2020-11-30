<script>
$('#fechainicio, #fechafin').keypress(function(e) {
    e.preventDefault()
});
var hrefmarcajes = $('#filtromarcaje').attr('enlace');
var hrefexmarcajes = $('#exportmarcajes').attr('enlace');
var hrefasistencias = $('#filtroasistencias').attr('enlace');
var hrefexasistencias = $('#exportasistencias').attr('enlace');
var hreffaltas = $('#filtrofaltas').attr('enlace');
var hrefexfaltas = $('#exportfaltas').attr('enlace');
var hreffichajes = $('#filtrofichajes').attr('enlace');
var hrefhorarios = $('#filtrohorarios').attr('enlace');
var hrefexhorarios = $('#exporthorarios').attr('enlace');
var hrefexfichajes = $('#exportfichajes').attr('enlace');
var hrefeliminarthorarios = $('#eliminar-t-horarios').attr('enlace');
var fecha = $('#fechainicio').attr('enlace');
var fecha2 = $('#fechafin').attr('enlace');
var profesor = $('#select_admon').val();
$('#fechainicio').on('change', function() {
    fecha = $(this).val(),
    $(function (){
        $('#fechafin').datepicker().focus();
    });
});
$('#fechafin').on('change', function() {
    fecha2 = $(this).val();
    if(profesor == null || profesor == '')
    {
        $('#filtromarcaje').attr('enlace', hrefmarcajes+'&fechainicio='+fecha+'&fechafin='+fecha2),
        $('#exportmarcajes').attr('enlace', hrefexmarcajes+'&fechainicio='+fecha+'&fechafin='+fecha2),
        $('#filtroasistencias').attr('enlace', hrefasistencias+'&fechainicio='+fecha+'&fechafin='+fecha2),
        $('#exportasistencias').attr('enlace', hrefexasistencias+'&fechainicio='+fecha+'&fechafin='+fecha2),
        $('#filtrofaltas').attr('enlace', hreffaltas+'&fechainicio='+fecha+'&fechafin='+fecha2),
        $('#exportfaltas').attr('enlace', hrefexfaltas+'&fechainicio='+fecha+'&fechafin='+fecha2),
        $('#filtrofichajes').attr('enlace', hreffichajes+'&fechainicio='+fecha+'&fechafin='+fecha2),
        $('#exportfichajes').attr('enlace', hrefexfichajes+'&fechainicio='+fecha+'&fechafin='+fecha2),
        $('#eliminar-t-horarios').attr('enlace', hrefeliminarthorarios+'&fechainicio='+fecha+'&fechafin='+fecha2)
    }
    else
    {
        $('#filtromarcaje').attr('enlace', hrefmarcajes+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#exportmarcajes').attr('enlace', hrefexmarcajes+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#filtroasistencias').attr('enlace', hrefasistencias+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#exportasistencias').attr('enlace', hrefexasistencias+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#filtrofaltas').attr('enlace', hreffaltas+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#exportfaltas').attr('enlace', hrefexfaltas+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#filtrofichajes').attr('enlace', hreffichajes+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#exportfichajes').attr('enlace', hrefexfichajes+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#filtrohorarios').attr('enlace', hrefhorarios+'&profesor='+profesor),
        $('#exporthorarios').attr('enlace', hrefexhorarios+'&profesor='+profesor),
        $('#eliminar-t-horarios').attr('enlace', hrefeliminarthorarios+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor)
    }
});
$('#select_admon').on('change', function() {
    profesor = $(this).val();
    if(fecha == null || fecha2 == null)
    {
        $('#filtromarcaje').attr('enlace', hrefmarcajes+'&profesor='+profesor),
        $('#exportmarcajes').attr('enlace', hrefexmarcajes+'&profesor='+profesor),
        $('#filtroasistencias').attr('enlace', hrefasistencias+'&profesor='+profesor),
        $('#exportasistencias').attr('enlace', hrefexasistencias+'&profesor='+profesor),
        $('#filtrofaltas').attr('enlace', hreffaltas+'&profesor='+profesor),
        $('#exportfaltas').attr('enlace', hrefexfaltas+'&profesor='+profesor),
        $('#filtrohorarios').attr('enlace', hrefhorarios+'&profesor='+profesor),
        $('#exporthorarios').attr('enlace', hrefexhorarios+'&profesor='+profesor),
        $('#filtrofichajes').attr('enlace', hreffichajes+'&profesor='+profesor),
        $('#exportfichajes').attr('enlace', hrefexfichajes+'&profesor='+profesor),
        $('#eliminar-t-horarios').attr('enlace', hrefeliminarthorarios+'&profesor='+profesor)
    }
    else
    {
        $('#filtromarcaje').attr('enlace', hrefmarcajes+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#exportmarcajes').attr('enlace', hrefexmarcajes+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#filtroasistencias').attr('enlace', hrefasistencias+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#exportasistencias').attr('enlace', hrefexasistencias+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#filtrofaltas').attr('enlace', hreffaltas+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#exportfaltas').attr('enlace', hrefexfaltas+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#filtrofichajes').attr('enlace', hreffichajes+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#exportfichajes').attr('enlace', hrefexfichajes+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor),
        $('#filtrohorarios').attr('enlace', hrefhorarios+'&profesor='+profesor),
        $('#exporthorarios').attr('enlace', hrefexhorarios+'&profesor='+profesor),
        $('#eliminar-t-horarios').attr('enlace', hrefeliminarthorarios+'&fechainicio='+fecha+'&fechafin='+fecha2+'&profesor='+profesor)
    }
});
</script>