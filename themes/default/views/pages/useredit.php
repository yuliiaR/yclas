<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		<div class="page-header">
			<h1><?=__('Edit Profile')?></h1>
		</div>
		<?= FORM::open(Route::url('profile',array('controller'=>'user','action'=>'edit', 'seoname'=>$user->seoname)), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
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
				<div class="control-group">
					<?= FORM::label('password1', __('New Password'), array('class'=>'control-label', 'for'=>'password1'))?>
					<div class="controls">
						<?= FORM::input('password1', '', array('class'=>'input-xlarge', 'id'=>'password1', 'type'=>'password','placeholder'=>__('new password')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('password2', __('Repeat Password'), array('class'=>'control-label', 'for'=>'password2'))?>
					<div class="controls">
						<?= FORM::input('password2', '', array('class'=>'input-xlarge', 'id'=>'password2', 'type'=>'password' ,'placeholder'=>__('repeat password')))?>
					</div>
				</div>
				<?if ($captcha_show !== 'FALSE'):?>
				<div class="control-group">
					<div class="controls">
						Captcha*:<br />
						<?=captcha::image_tag('contact');?><br />
						<?= FORM::input('captcha', "", array('class' => 'input-xlarge', 'id' => 'captcha', 'required'))?>
					</div>
				</div>
				<?endif?>

				<div class="form-actions">
					<?= FORM::button('submit', 'Change', array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('profile',array('controller'=>'user','action'=>'edit', 'seoname'=>$user->seoname))))?>
				</div>
		<?= FORM::close()?>
	</div>
	<!--/span-->
</div>
<!--/row-->