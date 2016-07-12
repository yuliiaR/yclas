<?php defined('SYSPATH') or die('No direct script access.');?>

<form class="register"  method="post" action="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>">         
	<div class="modal-body">
		<?=Form::errors()?>
		<div class="form-group clearfix">
			<label class="col-xs-12 control-label"><?=_e('Name')?></label>
			<div class="col-xs-12">
				<input class="form-control" type="text" name="name" value="<?=Request::current()->post('name')?>" placeholder="<?=__('Name')?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-xs-12 control-label"><?=_e('Email')?></label>
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
			<label class="col-xs-12 control-label"><?=_e('New password')?></label>
			<div class="col-xs-12 col-sm-8">
				<input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-xs-12 control-label"><?=_e('Repeat password')?></label>
			<div class="col-xs-12 col-sm-8">
				<input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
				<p class="help-block">
					<?=_e('Type your password twice')?>
				</p>
			</div>
		</div>
		<div class="form-group clearfix">
			<?if (core::config('advertisement.captcha') != FALSE OR core::config('general.captcha') != FALSE):?>
              	<?if (Core::config('general.recaptcha_active')):?>
	                <div class="col-sm-2"></div>
	                <div class="col-md-5 col-sm-6">
	                  	<?=Captcha::recaptcha_display()?> 
	                  	<div id="<?=isset($recaptcha_placeholder) ? $recaptcha_placeholder : 'recaptcha3'?>"></div>
	                </div>
              	<?else:?>
                	<label class="col-sm-2 control-label"><?=_e('Captcha')?>*:</label>
                	<div class="col-md-5 col-sm-6">
                  		<span id="helpBlock" class="help-block"><?=captcha::image_tag('register')?></span>
                  		<?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required', 'data-error' => __('Captcha is not correct')))?>
                	</div>
              	<?endif?>
            <?endif?>
		</div>
	</div>
	<div class="modal-foot-controls clearfix">
		<a class="btn btn-base-dark pull-left log-btn"  data-dismiss="modal" data-toggle="modal"  href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
			<?=_e('Login')?>
		</a>
		<button type="submit" class="btn btn-base-dark pull-right reg-btn"><?=_e('Register')?></button>
	</div>
	<?=Form::redirect()?>
	<?=Form::CSRF('register')?>
</form>