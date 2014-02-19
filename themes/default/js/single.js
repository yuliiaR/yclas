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