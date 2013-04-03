<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		<div class="page-header">
			<h1><?=__('Edit Profile')?></h1>
		</div>

		<?= FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
				<div class="control-group">
					<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
					<div class="controls">
						<?= FORM::input('name', $user->name, array('class'=>'input-xlarge', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
					<div class="controls">
						<?= FORM::input('email', $user->email, array('class'=>'input-xlarge', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
					</div>
				</div>
				

				<div class="form-actions">
					<?= FORM::button('submit', 'Change', array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))))?>
				</div>
		<?= FORM::close()?>
	</div>
	<!--/span-->
</div>
<!--/row-->