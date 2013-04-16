<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10 well">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Payments Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed payment configuration values. Replace input fileds with new desired values')?></p>
		</div>
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
					<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>
				<div class="control-group">
					<?= FORM::label($forms['sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['sandbox']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['sandbox']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['sandbox']['value'], array(
						'placeholder' => "TRUE or FALSE", 
						'class' => 'input-xlarge', 
						'id' => $forms['sandbox']['key'], 
						))?> 
						<a title="" data-content="<?=__("Sandbox")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['to_featured']['key'], __('To featured active'), array('class'=>'control-label', 'for'=>$forms['to_featured']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['to_featured']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE") ,$forms['to_featured']['value'], array(
						'placeholder' => '', 
						'class' => 'input-xlarge', 
						'id' => $forms['to_featured']['key'], 
						))?> 
						<a title="" data-content="<?=__("To featured active")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['to_top']['key'], __('To top active'), array('class'=>'control-label', 'for'=>$forms['to_top']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['to_top']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE") ,$forms['to_top']['value'], array(
						'placeholder' => "", 
						'class' => 'input-xlarge', 
						'id' => $forms['to_top']['key'], 
						))?> 
						<a title="" data-content="<?=__("To top active")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['pay_to_go_on_top']['key'], __('To top payment'), array('class'=>'control-label', 'for'=>$forms['pay_to_go_on_top']['key']))?>
					<div class="controls">
						<div class="input-prepend">
						<span class="add-on"><?=core::config('payment.paypal_currency')?></span>
							<?= FORM::input($forms['pay_to_go_on_top']['key'], $forms['pay_to_go_on_top']['value'], array(
							'placeholder' => "", 
							'class' => 'input-large', 
							'id' => "10", 
							));?> 
						<a title="" data-content="<?=__("To top payment")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
						</div>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['pay_to_go_on_feature']['key'], __('To featured payment'), array('class'=>'control-label', 'for'=>$forms['pay_to_go_on_feature']['key']))?>
					<div class="controls">
						<div class="input-prepend">
						<span class="add-on"><?=core::config('payment.paypal_currency')?></span>
							<?= FORM::input($forms['pay_to_go_on_feature']['key'], $forms['pay_to_go_on_feature']['value'], array(
							'placeholder' => "", 
							'class' => 'input-large ', 
							'id' => "10", 
							));?> 
						<a title="" data-content="<?=__("To featured payment")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
						</div>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['paypal_currency']['key'], __('Paypal currency'), array('class'=>'control-label', 'for'=>$forms['paypal_currency']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['paypal_currency']['key'], $paypal_currency , array_search($forms['paypal_currency']['value'], $paypal_currency), array(
						'placeholder' => "youremail@mail.com", 
						'class' => 'input-xlarge', 
						'id' => $forms['paypal_currency']['key'], 
						))?> 
						<a title="" data-content="<?=__("Paypal currency")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['paypal_account']['key'], __('Paypal accout'), array('class'=>'control-label', 'for'=>$forms['paypal_account']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['paypal_account']['key'], $forms['paypal_account']['value'], array(
						'placeholder' => "", 
						'class' => 'input-xlarge', 
						'id' => $forms['paypal_account']['key'], 
						))?> 
						<a title="" data-content="<?=__("Paypal accout")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>

				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
				</div>
			</fieldset>	
	</div><!--end span10-->
</div><!--end row-fluid-->