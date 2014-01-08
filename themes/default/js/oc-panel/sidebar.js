
//go to the latest tab, if it exists:
var collapsed_bar = localStorage.getItem('sidebar_state');

/*Side bar colapse*/
if($(window).width() > '750'){
	/*Bigger screens*/
	if($(window).width() < '1200'){ // when less than 1200px, do automatically small sidebar
		colapse_sidebar(true);
	}
	// on click triger
	$('.btn-colapse-sidebar').on('click', function(){
		colapse_sidebar($('.nav.nav-list.side-ul').hasClass('active'));
	});

	if(collapsed_bar == 'collapsed')
		colapse_sidebar(true);
	else
		colapse_sidebar(false);
}else{
	/*Mobile case*/
	// $('.btn-colapse-sidebar').parent().css('display','none'); // hide collapse button since it doesnt work here
	var sidebar = $('.respon-left-panel');
	var main_content = $('.main');
	sidebar.addClass('hide'); // when mobile always hide
	$('#mobile_header_btn, .btn-colapse-sidebar').on('click', function(){
		if(sidebar.hasClass('hide')){
			sidebar.removeClass('hide');
			main_content.css('margin-left','200px');
		}
		else{
			sidebar.addClass('hide');
			main_content.css('margin-left','auto');
		}
	});
	
}
/*
	Colapse sidebar function
	makes sidebar to mini sidear with only icons active
*/
function colapse_sidebar(event){

	if(event)
	{
		//set localstorage to be avare of current state of sidebar
		localStorage.setItem('sidebar_state', 'collapsed');

		$('.nav.nav-list.side-ul li').each(function(){
			$('a span.side-name-link', this).removeClass('active').addClass('hide'); // hide links in sidebar
			$('i', this).addClass('pos'); // remove class with padding
		});
		$('.nav.nav-list.side-ul').removeClass('active').addClass('colapsed');
		$('.nav.nav-list.side-ul').closest('aside').addClass('respones-colapse');
		$('.no-prem').hide(); // hide adverts
		$('.btn-colapse-sidebar').addClass('bla');
	}
	else
	{
		//set localstorage to be avare of current state of sidebar
		localStorage.setItem('sidebar_state', 'not-collapsed');

		$('.nav.nav-list.side-ul li').each(function(){
			$('a span.side-name-link', this).removeClass('hide').addClass('active');
			$('i', this).removeClass('pos'); // remove class with padding
		});

		$('.nav.nav-list.side-ul').removeClass('colapsed').addClass('active');
		$('.nav.nav-list.side-ul').closest('aside').removeClass('respones-colapse');
		$('.no-prem').show(); // show adverts
		$('.btn-colapse-sidebar').removeClass('bla');
	}
}

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


