
echo "<script>
$('#anterior-profesor').click(function(){
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=$anterior[ID]')
})
</script>";
echo "<script>
$('#siguiente-profesor').click(function(){
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=$siguiente[ID]')
})
</script>";
echo "<script>
$('#select-edit-guardias').on('change', function(){
profesor = $(this).val(),
$('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor='+profesor)
})
</script>";
echo "<script>
$('.act').hide();
$('.act').on('click', function(){
enlace = $(this).attr('enlace'),
$('#act-response').load(enlace),
setTimeout(function(){
$('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor='+$n[ID])
},200)
});
</script>";

echo "<script>
$('.edificio').on('change', function() {
edificio = $(this).val(),
id = $(this).attr('id').split('-'),
plus = 'plus-'+id[1]+'-'+id[2];
if(edificio == '')
{
    $('#'+plus).hide();
    return
}
else
{
    enlace = $('#'+plus).attr('enlace'),
    $('#'+plus).attr('enlace', enlace+'&e='+edificio),
    $('#'+plus).show()
}
});
</script>";