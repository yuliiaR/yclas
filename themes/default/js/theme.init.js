$(function(){
    
    $('textarea[name=description]').sceditorBBCodePlugin({
        toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
        "bulletlist,orderedlist|link,unlink,youtube|source",
        resizeEnabled: "true"
    });
    
    //sceditorBBCodePlugin for validation, updates iframe on submit 
$("button[name=submit]").click(function(){
    $("textarea[name=description]").data("sceditor").updateTextareaValue();
});
    
    if(!$("select").hasClass('disable-chosen')){
        $("select").chosen();   
    } 
    $("#category_subscribe").chosen(); 
    
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

    //online offline message
    window.addEventListener("offline", function(e) {
        $('.off-line').show();
    }, false);

    window.addEventListener("online", function(e) {
        $('.off-line').hide();
    }, false);

});

$(function() {

    if($('#gmap-address').attr('value') != null){
        var geocoder = new google.maps.Geocoder();
        var address = $('#gmap-address').attr('value');
        geocoder.geocode( { 'address': address}, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();
                var zoom_level = parseInt($('#gmap-address').attr('data-zoom'));
            } 
            new Maplace({
                show_markers: true,
                locations: [{
                    lat: latitude, 
                    lon: longitude,
                    zoom: zoom_level
                }]
            }).Load();
        });
    }
});

function setCookie(c_name,value,exdays)
{
var exdate = new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value = escape(value) + ((exdays==null) ? "" : ";path=/; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}