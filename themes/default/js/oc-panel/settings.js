//settins scripts 

// $('#allowed_formats option').each(function(){
// 	$(this).attr('selected', 'selected');
// });

// jQuery.validator with bootstrap integration
jQuery.validator.setDefaults({
    highlight: function(element) {
        jQuery(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        jQuery(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'label label-danger',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});

$('.config').validate();

$('.plan-add').click(function() {
    $("#modalplan input[name='featured_days']").val('');
    $("#modalplan input[name='featured_price']").val('');
    $("#modalplan input[name='featured_days_key']").val('');
});
$('.plan-edit').click(function() {
    $('#modalplan').modal('show');
    $("#modalplan input[name='featured_days']").val($(this).data('days'));
    $("#modalplan input[name='featured_days_key']").val($(this).data('days'));
    $("#modalplan input[name='featured_price']").val($(this).data('price'));
});
$('.plan-delete').click(function(e) {
    e.preventDefault();
    $(this).closest('li').slideUp();
    $.ajax({url: $(this).attr('href')});
});