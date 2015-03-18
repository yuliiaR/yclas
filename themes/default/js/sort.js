$(function (){
	$('.toolbar').each(function(){
		var id = '#'+$('.user-toolbar-options',this).attr('id');
		$(this).toolbar({
	        content: id,
	        hideOnClick: true, 
	    });
	});
	$('#toolbar-all').toolbar({
        content: '#user-toolbar-options-all',
        hideOnClick: true, 
    });
	 
});
var glyphicon_list = "<span class='glyphicon glyphicon-list-alt'></span> ";
var caret = " <span class='caret'></span>";
$('#sort-list li').each(function(){
  var replace_text = $('a', this).text();
  var href_text = $('a', this).attr('href').replace('?sort=','');
  if($('#sort').attr('data-sort') == href_text){
    $('#sort').html(glyphicon_list+replace_text+caret);
  }
});

$( "#sort-distance" ).click(function(e) {
    e.preventDefault()
    var url = $(this).attr('href');
    if(!readCookie('mylat') || !readCookie('mylng')) {
        var lat;
        var lng;
        GMaps.geolocate({
            success: function(position) {
                lat = position.coords.latitude;
                lng = position.coords.longitude
                setCookie('mylat',lat);
                setCookie('mylng',lng);
                window.location.href = url;
            },
            error: function(error) {
                alert('Geolocation failed: '+error.message);
            },
            not_supported: function() {
                alert("Your browser does not support geolocation");
            },
        });
    }
    else {
        window.location.href = $(this).attr('href');
    }
});