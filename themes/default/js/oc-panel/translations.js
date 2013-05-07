$(function(){
    $('.button-copy').click(function(event) {
        event.preventDefault();          
        var orig = $(this).data('orig');
        var dest = $(this).data('dest');
           $('#'+dest).val($('#'+orig).val());
    });

    $('#button-copy-all').click(function(event) {
         event.preventDefault();      
        $('.button-copy').each(function() {
            var orig = $(this).data('orig');
            var dest = $(this).data('dest');
            $('#'+dest).val($('#'+orig).val());
        });
    });

});