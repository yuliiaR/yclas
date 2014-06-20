$(function(){
    
    //sceditorBBCodePlugin for validation, updates iframe on submit 
$("button[name=submit]").click(function(){
    $("textarea[name=description]").data("sceditor").updateTextareaValue();
});
    
    //chosen enable/disable
    $('select').chosen();
    $("#category_subscribe").chosen(); 
    $('select').each(function(){
        if($(this).hasClass('disable-chosen')){
            $(this).chosen('destroy');      
        } 
    });
    
    $('input, select, textarea, .btn').tooltip();

    //datepicker in case date field exists
    if($('.cf_date_fields').length != 0){
        $('.cf_date_fields').datepicker();}

	$('.tips').popover();

	$('.slider_subscribe').slider();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');

    $(window).load(function(){
        $('#accept_terms_modal').modal('show');
    });

    //publish new loading button
    $('#publish-new-btn').click(function () {
        if ($("#publish-new").valid()) {
            $(this).button('loading');
        }
    });

    //online offline message
    window.addEventListener("offline", function(e) {
        $('.off-line').show();
    }, false);

    window.addEventListener("online", function(e) {
        $('.off-line').hide();
    }, false);

});

function setCookie(c_name,value,exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays==null) ? "" : ";path=/; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}
