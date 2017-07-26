//datepicker in case date field exists
if($('.cf_date_fields').length != 0){
    $('.cf_date_fields').datepicker();
}

$('#phone').intlTelInput({
    formatOnDisplay: false,
});

$('form').submit(function() {
    $('#phone').val($('#phone').intlTelInput("getNumber"));
});
