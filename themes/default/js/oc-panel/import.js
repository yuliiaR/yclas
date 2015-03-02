$(function(){
    $("#import_process").click(function() {
        var href = $(this).attr('href');
        event.preventDefault();
        $(this).text("Processing...");
        $("#delete_queue").hide();
        process(href);            
    }); 
});


function process(href)
{
    $.ajax({ url: href,
        }).done(function ( data ) {

            $("#count_import").text(data+"%");

            if (isNumeric(data) && data < 100)
                process(href);
            else
                $("#import_process").hide();
            
    });
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}