// Initiate the metisMenu
$(function() {
    $('.sidebar li.active').each(function() {
        $(this).parent().parent().addClass('active');
        $(this).parent().parent().find('a').attr('aria-expanded', true);
    });
    $('#side-menu').metisMenu();
});

//go to the latest tab, if it exists:
var collapsed_bar = $.cookie('sidebar_state');

/*Side bar colapse*/
if (collapsed_bar == 'collapsed')
    colapse_sidebar(true);
else
    colapse_sidebar(false);

// Collapse the sidebar menu
$('#collapse-menu').click(function() {
    colapse_sidebar(! $('body').hasClass('folded'));
});

/*
  Colapse sidebar function
  makes sidebar to mini sidear with only icons active
*/

function colapse_sidebar(event) {
    if (event) {
        $('body').addClass('folded');

        //set cookie to be avare of current state of sidebar
        $.cookie('sidebar_state', 'collapsed', { expires: 7, path: '/' });

        $('.sidebar li.active').each(function() {
            $(this).removeClass('active');
            $(this).find('a').attr('aria-expanded', false); // hide links in sidebar
            $(this).find('ul').each(function() {
                $(this).attr('aria-expanded', false).removeClass('in'); // hide links in sidebar
            });
        });
    }
    else {
        $('body').removeClass('folded');

        //set cookie to be avare of current state of sidebar
        $.cookie('sidebar_state', 'not-collapsed', { expires: 7, path: '/' });
    }
}

//minified sidebar,when click outside close dropdown
$(document).click(function() {
    if ($('body').hasClass('folded')) {
        $('.sidebar li').each(function() {
            $(this).removeClass('active');
            $(this).find('a').attr('aria-expanded', false); // hide links in sidebar
            $(this).find('ul').each(function() {
                $(this).attr('aria-expanded', false).removeClass('in'); // hide links in sidebar
            });
        });
    }
});
