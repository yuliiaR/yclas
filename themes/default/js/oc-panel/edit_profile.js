//datepicker in case date field exists
if($('.cf_date_fields').length != 0){
    $('.cf_date_fields').datepicker();
}

if ($('input[name="phone"]').length != 0) {
    $('input[name="phone"]').intlTelInput({
        formatOnDisplay: false,
        initialCountry: $('input[name="phone"]').data('country')
    });

    $('form').submit(function() {
        var $phoneField = $(this).find('input[name="phone"]');
        $phoneField.val($phoneField.intlTelInput("getNumber"));
    });
}
