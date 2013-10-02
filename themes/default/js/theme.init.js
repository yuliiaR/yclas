$(function(){
    
    $('textarea[name=description], .cf_textarea_fields').sceditorBBCodePlugin({
        toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
        "bulletlist,orderedlist|link,unlink,youtube|source",
        resizeEnabled: "true"
    });
    
    $("select").chosen();
    
    $('.btn').tooltip();

	$('.tips').popover();

	$('.slider_subscribe').slider();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');

});