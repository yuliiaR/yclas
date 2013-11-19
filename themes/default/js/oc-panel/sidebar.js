$(function() {
	$('.dropdown-sidebar.sbp.active .submenu').addClass('active');
	$('.dropdown-sidebar.sbp.active .dropdown-toggle .glyphicon-chevron-down')
			.removeClass('glyphicon-chevron-down')
			.addClass('glyphicon-chevron-up');
	$('.dropdown-sidebar').on('click',function(){
		dropdown($(this));
	});
});

function dropdown(event){
	var active = $('.submenu',event);
	if(active.hasClass('active'))
	{
		active.removeClass('active');
		$('.dropdown-toggle .glyphicon-chevron-up', event)
			.removeClass('glyphicon-chevron-up')
			.addClass('glyphicon-chevron-down');
	}
	else
	{
		active.addClass('active');
		$('.dropdown-toggle .glyphicon-chevron-down', event)
			.removeClass('glyphicon-chevron-down')
			.addClass('glyphicon-chevron-up');
	}
}
