$(document).ready(function(){
	$(init);
	function init(){
		$('.makeMeDraggable').draggable({
			helper: myHelper
		});
		$('.makeMeDroppable').droppable( {
    		drop: handleDropEvent
  		});
	}

	function handleDropEvent( event, ui ) {
  		var draggable = ui.draggable;
  		var placeholder = ui.draggable.attr('name');
  		// alert(placeholder);
  		new_widget = $('#drop-here').append('<tr><td class="new_widget active">Add new</td><td><div class="well advise clearfix"><p>'+
  											draggable.attr('id') + '</p></div>'+
  											'<div class="btn-group"><a class="btn btn-mini" href="/oc-panel/widget/save/?'+
  											draggable.attr("id")+
  											'='+placeholder+'">Save</a></div>'+
  											'<div class="btn-group"><a class="btn btn-mini" href="/oc-panel/widget/index/'+
  											placeholder+'">Cancel</a></div></td><td><p>Hint! <br/> To add more widget click "Save" button first, <br/>'+
  											' or "Cancel" to cancel operation</p></td></tr>');
  		$(block_drop_area);
	}

	

	function myHelper( event ) {
		name = $(this).text();
  		return '<div class="well advise clearfix">'+name+'</div>';
	}

	function block_drop_area(){
		if($('.new_widget').length) {
			$('.makeMeDroppable').hide();
		}
	}

});
  