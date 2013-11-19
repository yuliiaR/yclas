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
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        messages: {
            price:{regex: "Format is incorect"}
        }
    });
    
    //chosen fix
    var settings = $.data($form[0], 'validator').settings;
    settings.ignore += ':not(#location)'; // post_new location(any chosen) texarea
    settings.ignore += ':not(#description)'; // post_new description texarea
    
    // $('.chosen-container a').ready(function(){
        
    //     // $('.chosen-single').hasClass('chosen-default').addClass('has-error');
    //     if($(this).hasClass('chosen-default'))
    //     {
    //         $(this).addClass('has-error');
    //     }
    //     else
    //     {
    //         $(this).removeClass('has-error');
    //     }
    // });
    
    // end VALIDATION

    //datepicker in case date field exists
    if($('.cf_date_fields').length != 0){
        $('.cf_date_fields').datepicker();}