$(document).ready(function(){
    $('#btnPrintHello').click(function(){
        $.ajax({
            url: "http://localhost/printTicket/printHello.php",
            type: "POST",
            success: function(ans){
                console.log(ans);
            }
        })
    });
    $('#btnPrintClose').click(function(){
        var datos = new FormData();
        var arrayT = [{ID: "1", Precio: "20.5"},{ID: "3", Precio: "5.25"}];
            
        $.ajax({
            url: "http://localhost/printTicket/printTicket.php?array="+JSON.stringify(arrayT),
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            // dataType:"json",
            success: function(ans){
                console.log(ans);
            }
        });

    });
});