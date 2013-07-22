$(function(){
    
    $('textarea[name=description]').sceditorBBCodePlugin({
        toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
        "bulletlist,orderedlist|link,unlink,youtube|source",
        resizeEnabled: "true"
    });

    $("input,textarea").not("[type=submit]").jqBootstrapValidation(); 
 
    $('.btn').tooltip();

	$('.tips').popover();

	$('.slider_subscribe').slider();

    $("select").chosen();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');



});