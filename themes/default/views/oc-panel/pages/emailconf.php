<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Email Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed general configuration values. Replace input fileds with new desired values')?></p>
		</div>
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'emailconf')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
					<div class="control-group">
						<?= FORM::label($c->config_key, __($c->config_key), array('class'=>'control-label', 'for'=>$c->config_key))?>
						<div class="controls">
							<?= FORM::input($c->config_key, $c->config_value, array(
							'placeholder' => $c->config_value, 
							'class' => 'input-xlarge', 
							'id' => $c->config_key, 
							))?> 
						</div>
					</div>
				<?endforeach?>
				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'emailconf'))))?>
				</div>
			</fieldset>	
	</div><!--end span10-->
</div><!--end row-fluid-->