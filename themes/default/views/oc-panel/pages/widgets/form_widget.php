<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="well">

	<h3><?=$widget->title?></h3>
	<p><?=$widget->description?></p>

	<form method="post" action="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'test'))?>">
		<?foreach ($tags as $tag):?>
			<?=$tag?>
		<?endforeach?>

		<input type="hidden" name="widget" value="<?=$widget->widget_name?>" >

		<div class="form-actions">
		  <button type="submit" class="btn btn-primary">Save changes</button>
		  <button type="button" class="btn">Cancel</button>
		</div>

	</form>


</div>

