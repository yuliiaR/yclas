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
   
    // VALIDATION with chosen fix
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Please check your input."
    );

    var $form = $(".post_new");
    $form.validate({
        errorLabelContainer: $(".post_new div.error"),
        wrapper: 'div',
        rules: {
            title: {minlength:2},
            price: {regex:"^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"}
        },
        messages: {
            price:{regex: "Format is incorect"}
        }
    });
    
    //chosen fix
    var settings = $.data($form[0], 'validator').settings;
    settings.ignore += ':not(.chzn-done)'; // post_new location(any chosen) texarea
    settings.ignore += ':not(#description)'; // post_new description texarea
    settings.ignore += ':not(.cf_textarea_fields)';//post_new texarea custom fields
    // end VALIDATION

    // $('input[required], input[type=text]').unbind('keydown');
    // $('input[required], input[type=text]').unbind('keypress');
    // $('input[required], input[type=text]').unbind('keyup');

});