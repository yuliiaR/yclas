$(function(){
    
    //favorites system
	$('.add-favorite, .remove-favorite').click(function(event) {
		  event.preventDefault();
		  $this = $(this);
		  $.ajax({ url: $this.attr('href'),
				}).done(function ( data ) {
					$('#'+$this.data('id')+' a').toggleClass('add-favorite remove-favorite');
					$('#'+$this.data('id')+' a i').toggleClass('glyphicon-heart-empty glyphicon-heart');
				});
	});

});

$(function(){

    //notification system
    var favicon = new Favico({
        animation : 'popFade'
    });

    $('#contact-notification').click(function(event) {
        $.get($(this).data('url'));
        $(document).mouseup(function (e)
        {
            var contact = $("#contact-notification");
        
            if (!contact.is(e.target) // if the target of the click isn't the container...
                && contact.has(e.target).length === 0) // ... nor a descendant of the container
            {
                //$("#contact-notification").slideUp();
                $("#contact-notification span").hide();
                $("#contact-notification i").removeClass('fa-bell').addClass('fa-bell-o');
                $("#contact-notification-dd" ).remove();
                favicon.badge(0);
            }
        });
    });
    
    //intial value
    favicon.badge($('#contact-notification span').text());
});

//validate auth pages
$(function(){
    
    var $params = {rules:{}, messages:{}};
    $params['rules']['email'] = {required: true, email: true};

    $(".auth").each(function() {
        $(this).validate($params)
    });

    var $register_params = {rules:{}, messages:{}};
    $register_params['rules']['email'] = {required: true, email: true};
    $register_params['rules']['password1'] = {required: true};
    $register_params['rules']['password2'] = {required: true};

    $(".register").each(function() {
        $(this).validate($register_params)
    });
    
});

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

$(function(){
    if ($('input[name="auto_locate"]').length) {
        $('#auto-locations').modal('show');
        if(!readCookie('mylat') || !readCookie('mylng')) {
            var lat;
            var lng;
            GMaps.geolocate({
                success: function(position) {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude
                    setCookie('mylat',lat);
                    setCookie('mylng',lng);
                    // show modal
                    $.get($('meta[name="application-name"]').data('baseurl'), function(data) {
                        $('input[name="auto_locate"]').after($(data).find("#auto-locations"));
                        $('#auto-locations').modal('show');
                    })
                },
                error: function(error) {
                    alert('Geolocation failed: '+error.message);
                },
                not_supported: function() {
                    alert("Your browser does not support geolocation");
                },
            });
        }
    }
});