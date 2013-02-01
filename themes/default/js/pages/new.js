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



var href_del 	= $('a.delete').attr('href');
var href_spam 	= $('a.spam').attr('href');
var href_deact 	= $('a.deactivate').attr('href');

// selected checkboxes get new class
var selected 	= '';
$('input.checkbox').click(function(){
	if($(this).is(':checked')){
		$(this).addClass("selected");
		
		//loop to colect all id-s for checked advert-s
		selected = '';
		$('input.selected').each(function(){
			selected += ($(this).attr('id'));
		});

		//append new href with id-s
		$('a.delete').attr('href', href_del+'/'+selected);
		$('a.spam').attr('href', href_spam+'/'+selected);
		$('a.deactivate').attr('href', href_deact+'/'+selected);
	}else{

		$(this).removeClass("selected");

		selected = '';
		$('input.selected').each(function(){
			selected += ($(this).attr('id'));
		});

		// back to original href
		$('a.spam').attr('href', "/oc-panel/ad/spam");
		$('a.deactivate').attr('href', "/oc-panel/ad/deactivate");
		$('a.delete').attr('href', "/oc-panel/ad/delete");
	}
			alert(selected);
});


//select all checkboxes and append class to all
function check_all(){
	var selected 	= '';

	if($('#select-all').is(':checked')){
		$('input.checkbox').addClass('selected').attr('checked', true);
		$('input.selected').each(function(){
			selected += ($(this).attr('id'));
		});
		$('a.delete').attr('href', href_del+'/'+selected);
		$('a.spam').attr('href', href_spam+'/'+selected);
		$('a.deactivate').attr('href', href_deact+'/'+selected);
	}else{
		selected = '';
		$('input.checkbox').removeClass('selected').attr('checked', false);
		$('a.spam').attr('href', "/oc-panel/ad/spam");
		$('a.deactivate').attr('href', "/oc-panel/ad/deactivate");
		$('a.delete').attr('href', "/oc-panel/ad/delete");
	}
}


