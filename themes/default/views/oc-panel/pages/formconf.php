<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Form Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed only form fields that are optional. To activate them type "TRUE/FALSE" in wanted field. ')?></p>
		</div>
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'formconf')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<h3><?=__('Category')?><h3>
				<div class="control-group">
					<?= FORM::label('id_category_parent', __('Id Category Parent'), array('class'=>'control-label', 'for'=>'id_category_parent'))?>
					<div class="controls">
						<?= FORM::input('id_category_parent', '', array('placeholder' => 'TRUE', 'class' => 'input-xlarge', 'id' => 'id_category_parent'))?>
					</div>
					<div class="form-actions">
						<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'formconf'))))?>
					</div>
				</div>
			</fieldset>
		<?= FORM::close()?>
	</div><!--end span 10-->
</div><!--end row-->