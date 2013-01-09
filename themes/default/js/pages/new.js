$(function(){
	
	$('textarea[name=description]').sceditorBBCodePlugin({
		toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
		"bulletlist,orderedlist|link,unlink,youtube|source",
		resizeEnabled: "true"
	});
$(function () { 

	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
 
	$('.btn').tooltip(); 
	//$('textarea[name=description]').autogrow();
});