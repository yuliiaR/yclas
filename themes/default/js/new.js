

    $('textarea[name=description]').sceditorBBCodePlugin({
    toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
    "bulletlist,orderedlist|link,unlink,youtube|source",
    resizeEnabled: "true"
    });
    // google map set marker on address

if($('#map').length != 0){
new GMaps({
  div: '#map',
  zoom: parseInt($('#map').attr('data-zoom')),
  lat: $('#map').attr('data-lat'),
  lng: $('#map').attr('data-lon')
}); 
var typingTimer;                //timer identifier
var doneTypingInterval = 500;  //time in ms, 5 second for example
//on keyup, start the countdown
$('#address').keyup(function(){
    clearTimeout(typingTimer);
    if ($(this).val()) {
       typingTimer = setTimeout(doneTyping, doneTypingInterval);
    }
});
//user is "finished typing," refresh map
function doneTyping () {
    GMaps.geocode({
      address: $('#address').val(),
      callback: function(results, status) {
        if (status == 'OK') {
          var latlng = results[0].geometry.location;
            map = new GMaps({
              div: '#map',
              lat: latlng.lat(),
              lng: latlng.lng(),

            }); 
          map.setCenter(latlng.lat(), latlng.lng());
          map.addMarker({
            lat: latlng.lat(),
            lng: latlng.lng()
          });
        }
      }
    });
}
}

    // VALIDATION with chosen fix
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Please check your input."
    );

    // some extra rules for custom fields
    if($('.cf_decimal_fields').length != 0)
        var $decimal = $(".cf_decimal_fields").attr("name");
    if($('.cf_integer_fields').length != 0)
        var $integer = $(".cf_integer_fields").attr("name");
    
    var $params = {rules:{}, messages:{}};
    $params['rules'][$integer] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['messages'][$integer] = "Format is incorect";
    $params['rules'][$decimal] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['messages'][$decimal] = "Format is incorect";
    $params['rules']['price'] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['messages']['price'] = "Format is incorect";

    $.validator.setDefaults({ ignore: ":hidden:not(select)" });
    var $form = $(".post_new");
    $form.validate($params);
    
    //chosen fix
    var settings = $.data($form[0], 'validator').settings;
    settings.ignore += ':not(#location)'; // post_new location(any chosen) texarea
    settings.ignore += ':not([name="description"])'; // post_new description texarea

    
    // end VALIDATION

    //datepicker in case date field exists
    if($('.cf_date_fields').length != 0){
        $('.cf_date_fields').datepicker();}

    // activate for each level chained select
    $('.category_chained_select').each(function(){
        var level = $(this).data('level');
        if('#level-'+(level-1)){
            $('#level-'+level).chained('#level-'+(level-1));
        }
    });

    // this will select the correct ID for uploading category
    $( ".category_chained_select" ).change(function() {

      $( "option:selected", this ).each(function() {
            var value_category_id = $(this).attr('value');

            if($(this).parent().hasClass('is_parent') || $(this).parent().data('level') > 0){

                $('#category-selected').attr('value',value_category_id);
                $('.category-price').text('');
                if($(this).data('price') > 0)
                    $('.category-price').text($(this).data('price'));
            }
                //coloring select, for user to know if he select option is taken or not
                if($('#category-selected').attr('value') != ''){
                    //adding green color, success
                    $(this).parent().css('background','#dff0d8');
                    $('.selected-category').html($('.category_chained_select option[value='+$('#category-selected').attr('value')+']').text()).one();
                }
        
                if($('#category-selected').attr('value') == ''){
                    $(this).parent().css('background','#fff');
                    $('.selected-category').html('');
                }
                     
        });
                
    });

    var categ_selected = $('.category_chained_select option:selected').attr('value');
    $("input[name=category]").attr('value', categ_selected);
    