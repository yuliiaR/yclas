$(function(){
	
	$('textarea[name=description]').sceditorBBCodePlugin({
		toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
		"bulletlist,orderedlist|link,unlink,youtube|source",
		resizeEnabled: "true"
	});
	//$('textarea[name=description]').autogrow();
});