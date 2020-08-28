function create()
{
    data=$("#data").val();
    $("#qrimage").html("<img src='https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl="+encodeURI(data)+"'/>");
}
