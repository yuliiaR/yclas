$(function(){
	
	$('textarea[name=description]').sceditorBBCodePlugin({
		toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
		"bulletlist,orderedlist|link,unlink,youtube|source",
		resizeEnabled: "true"
	});

// jqtwitterbootstrap form validation 
$(function () {

	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
 
	$('.btn').tooltip();
	//$('textarea[name=description]').autogrow();
});

// // show category price
// $("#category").change(function(){
// 	var price = "Price for this category is: ";

// 	$("#category option:selected").each(function(){
// 		price += $(this).text() + " ";
// 	});
// 	$("#cat_price").text(price);
	
// });





