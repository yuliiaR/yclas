<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="control-group">
	<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
	<div class="controls">
		<?= FORM::input('title', Request::current()->post('title'), array('placeholder' => __('Title'), 'class' => 'input-xlarge', 'id' => 'title', 'required'))?>
	</div>
	<?=Widget::$placeholder_name;?>
</div>
