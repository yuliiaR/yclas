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
        setCookie("contact_notification", Math.round(+new Date()/1000), 365);
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