$(function(){

    //sceditorBBCodePlugin for validation, updates iframe on submit
$("button[name=submit]").click(function(){
    $("textarea[name=description]").data("sceditor").updateOriginal();
});

    //select2 enable/disable
    $('select').select2({
        "language": "es"
    });
    $('select').each(function(){
        if($(this).hasClass('disable-select2')){
            $(this).select2('destroy');
        }
    });
    // Fixes select2 on bootstrap modals
    $('#register-modal select').each(function(){
        $(this).select2('destroy');
        $(this).select2({
            "dropdownParent": $('#register-modal'),
            "language": "es",
            "templateResult": function(result, container) {
                if ( ! result.id) {
                    return result.text;
                }
                container.className += ' needsclick';
                return result.text;
            }
        });
    });
    //select2 responsive width
    $(window).on('resize', function() {
        $('select').each(function(){
            var width = $(this).parent().width();
            $(this).siblings('.select2-container').css({'width':width});
        });
    }).trigger('resize');

    $('input, select, textarea, .btn').tooltip();

    //datepicker in case date field exists
    if($('.cf_date_fields').length != 0){
        $('.cf_date_fields').datepicker({
            autoclose: true
        });}

	$('.tips').popover();

	$('.slider_subscribe').slider();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');

    $(window).load(function(){
        $('#accept_terms_modal').modal('show');
    });

});

$(function(){
    var maxHeight = 0;
    $(".latest_ads").each(function() {
        if ($(this).outerHeight() > maxHeight) {
            maxHeight = $(this).outerHeight();
        }
    }).height(maxHeight);
});

$(function(){
    var bodyPaddingTop = $("header").height();
    bodyPaddingTop=(+bodyPaddingTop)+10;
    document.body.style.paddingTop = bodyPaddingTop+'px';
});