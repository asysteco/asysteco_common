<script>
    $('.row_show').on('click', function() {
        id = $(this).parent().attr('id').split('_'),
        id = id[1],
        $("#horario").load('index.php?ACTION=profesores&profesor=' + id),
        $("html, body").animate({ scrollTop: 0 })
    });
</script>