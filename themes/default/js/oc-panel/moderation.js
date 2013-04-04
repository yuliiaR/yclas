/**
 * [MODERATION / ADVERT. selectbox script]
 * 
 */
var href_del = $('a.delete').attr('href');
var href_spam = $('a.spam').attr('href');
var href_deact = $('a.deactivate').attr('href');
var href_active = $('a.activate').attr('href');
var href_feature = $('a.featured').attr('href');
var href_deact_feature = $('a.featured').attr('href');

// selected checkboxes get new class
var selected = '';
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
		$('a.activate').attr('href', href_active+'/'+selected);
		$('a.featured').attr('href', href_feature+'/'+selected);
		$('a.featured').attr('href', href_deact_feature+'/'+selected);
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
		$('a.activate').attr('href', href_active+'/'+selected);
		$('a.featured').attr('href', "/oc-panel/ad/featured");
	}
});


//select all checkboxes and append class to all
function check_all(){
	var selected = '';

	if($('#select-all').is(':checked')){
		$('input.checkbox').addClass('selected').attr('checked', true);
		$('input.selected').each(function(){
			selected += ($(this).attr('id'));
		});
		$('a.delete').attr('href', href_del+'/'+selected);
		$('a.spam').attr('href', href_spam+'/'+selected);
		$('a.deactivate').attr('href', href_deact+'/'+selected);
		$('a.activate').attr('href', href_active+'/'+selected);
		$('a.featured').attr('href', href_feature+'/'+selected);
		$('a.featured').attr('href', href_deact_feature+'/'+selected);
	}else{
		selected = '';
		$('input.checkbox').removeClass('selected').attr('checked', false);
		$('a.spam').attr('href', "/oc-panel/ad/spam");
		$('a.deactivate').attr('href', "/oc-panel/ad/deactivate");
		$('a.delete').attr('href', "/oc-panel/ad/delete");
		$('a.activate').attr('href', href_active+'/'+selected);
		$('a.featured').attr('href', "/oc-panel/ad/featured");
		$('a.featured').attr('href', "/oc-panel/ad/featured");
	}
}