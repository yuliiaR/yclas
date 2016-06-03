<?php defined('SYSPATH') or die('No direct script access.');?>

<form class="register"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>">         
	<div class="modal-body">
		<?=Form::errors()?>
		<div class="form-group clearfix">
			<label class="col-xs-12 control-label"><?=__('Name')?></label>
			<div class="col-xs-12">
				<input class="form-control" type="text" name="name" value="<?=Request::current()->post('name')?>" placeholder="<?=__('Name')?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-xs-12 control-label"><?=__('Email')?></label>
			<div class="col-xs-12">
				<input
					class="form-control" 
					type="text" 
					name="email" 
					value="<?=Request::current()->post('email')?>" 
					placeholder="<?=__('Email')?>" 
					data-domain='<?=(core::config('general.email_domains') != '') ? json_encode(explode(',', core::config('general.email_domains'))) : ''?>' 
					data-error="<?=__('Email must contain a valid email domain')?>"
				>
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-xs-12 control-label"><?=__('New password')?></label>
			<div class="col-xs-12 col-sm-8">
				<input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-xs-12 control-label"><?=__('Repeat password')?></label>
			<div class="col-xs-12 col-sm-8">
				<input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
				<p class="help-block">
					<?=__('Type your password twice')?>
				</p>
			</div>
		</div>
	</div>
	<div class="modal-foot-controls clearfix">
		<a class="btn btn-base-dark pull-left log-btn"  data-dismiss="modal" data-toggle="modal"  href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
			<?=__('Login')?>
		</a>
		<button type="submit" class="btn btn-base-dark pull-right reg-btn"><?=__('Register')?></button>
	</div>
	<?=Form::redirect()?>
	<?=Form::CSRF('register')?>
</form>