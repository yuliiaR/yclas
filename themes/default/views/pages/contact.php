<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="well">
	<?=Form::errors()?>
	<h1><?=__('Contact Us')?></h1>
	<?= FORM::open(Route::url('contact'), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
	<fieldset>
		<div class="form-group">
			<div class="col-md-4 ">
			<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
				<?= FORM::input('name', '', array('placeholder' => __('Name'), 'class' => 'form-control', 'id' => 'name', 'required'))?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-4 ">
				<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
				<?= FORM::input('email', '', array('placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'email', 'type'=>'email','required'))?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-4 ">
				<?= FORM::label('subject', __('Subject'), array('class'=>'control-label', 'for'=>'subject'))?>
				<?= FORM::input('subject', "", array('placeholder' => __('Subject'), 'class' => 'form-control', 'id' => 'subject'))?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-4">
				<?= FORM::label('message', __('Message'), array('class'=>'control-label', 'for'=>'message'))?>
				<?= FORM::textarea('message', "", array('class'=>'form-control', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>7, 'required'))?>	
			</div>
		</div>
		
		<?if (core::config('advertisement.captcha') != FALSE):?>
		<div class="form-group">
			<div class="col-md-4">
				<?=__('Captcha')?>*:<br />
				<?=captcha::image_tag('contact')?><br />
				<?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
			</div>
		</div>
		<?endif?>
		<div class="form-group">
			<div class="col-md-4">
				<?= FORM::button('submit', __('Contact Us'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('contact')))?>
			</div>
			<br class="clear">
		</div>
	</fieldset>
	<?= FORM::close()?>

</div><!--end col-md-10-->