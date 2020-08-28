$(window).on('scroll', function () {
    if($(document).scrollTop() > 150) {
        $("#flecha_div").addClass("flecha_div_visible"),
        $("#flecha").addClass("jsflecha")
    }
    else{
        $("#flecha").addClass("flecha"),
        $("#flecha").removeClass("jsflecha"),
        $("#flecha_div").removeClass("flecha_div_visible")
    }
});