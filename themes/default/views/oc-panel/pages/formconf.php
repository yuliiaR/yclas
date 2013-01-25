<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Form Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed only form fields that are optional. To activate/deactiave please type "TRUE/FALSE" in desired field. ')?></p>
		</div>
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'formconf')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($fields as $element => $value): ?>
				<h3><? echo strtoupper(__($element))?><h3>
					<?= FORM::input($element, $element, array('placeholder' => $element, 'class' => 'input-xlarge', 'id' => $element, 'type'=>'hidden'))?>
					<?foreach($value as $elem => $val):?>
					<?if($val == 1) $val = "TRUE"; else $val = "FALSE";?>	
					<div class="control-group">
						<?= FORM::label($elem, __($elem), array('class'=>'control-label', 'for'=>$elem))?>
						<div class="controls">

							
						
							<?= FORM::input($element.$elem, $val, array('placeholder' => $val, 'class' => 'input-xlarge', 'id' => $element.$elem))?>
						</div>
					</div>
					<?endforeach?>
				<?endforeach?>
				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'formconf'))))?>
				</div>
			</fieldset>
		<?= FORM::close()?>
	</div><!--end span 10-->
</div><!--end row-->