$('textarea[name=message]:not(.disable-bbcode)').sceditorBBCodePlugin({
    toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
    "bulletlist,orderedlist|link,unlink,youtube|source",
    resizeEnabled: "true",
    style: $('meta[name="application-name"]').data('baseurl') + "themes/default/css/jquery.sceditor.default.min.css",
	emoticonsEnabled: false
});
	
// paste plain text in sceditor
$(".sceditor-container iframe").contents().find("body").bind('paste', function(e) {
	e.preventDefault();
	var text = (e.originalEvent || e).clipboardData.getData('text/plain');
	$(".sceditor-container iframe")[0].contentWindow.document.execCommand('insertText', false, text);
});

$(".message").hover(function() {
        $(this).css('cursor','pointer');
    },
    function() {
        $(this).css('cursor','auto');
});

$(".message").click(function() {
    window.location = $(this).data("url"); 
    return false;
});