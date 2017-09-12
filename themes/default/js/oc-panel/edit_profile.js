//datepicker in case date field exists
if($('.cf_date_fields').length != 0){
    $('.cf_date_fields').datepicker();
}

$('.fileinput').on('change.bs.fileinput', function() {
    //unhide next box image after selecting first
    $(this).next('.fileinput').removeClass('hidden');
});
