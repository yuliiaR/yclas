<?php defined('SYSPATH') or die('No direct script access.');?>

<button  class="drop btn btn-large" data-toggle="modal" data-target="#<?=$widget->id_name()?>" type="button"><?=$widget->title?></button>


<div id="<?=$widget->id_name()?>" class="modal hide fade" role="dialog" aria-labelledby="<?=$widget->id_name()?>" aria-hidden="true">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?=$widget->description?></h3>
  </div>

  <div class="modal-body">
    <form class="well" method="post" action="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'test'))?>" >
		<?foreach ($tags as $tag):?>
			<?=$tag?>
		<?endforeach?>
		<?if ($widget->loaded):?>
		<input type="hidden" name="widget_name" value="<?=$widget->widget_name?>" >
		<?endif?>
	</form>
  </div>

  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true"><?=__('Close')?></button>
    <a href="#" class="btn btn-primary"><?=__('Save changes')?></a>
  </div>

</div>