<?php defined('SYSPATH') or die('No direct script access.');?>

	
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Payments Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed payment configuration values. Replace input fileds with new desired values')?></p>
		</div>

		<div class="well">
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
						'class' => 'tipsti', 
						'id' => $forms['sandbox']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['paypal_currency']['key'], __('Paypal currency'), array('class'=>'control-label', 'for'=>$forms['paypal_currency']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['paypal_currency']['key'], $paypal_currency , array_search($forms['paypal_currency']['value'], $paypal_currency), array(
						'placeholder' => "youremail@mail.com", 
						'class' => 'tips', 
						'id' => $forms['paypal_currency']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['to_featured']['key'], __('To featured active'), array('class'=>'control-label', 'for'=>$forms['to_featured']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['to_featured']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE") ,$forms['to_featured']['value'], array(
						'placeholder' => '', 
						'class' => 'tips', 
						'id' => $forms['to_featured']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 

						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['to_top']['key'], __('To top active'), array('class'=>'control-label', 'for'=>$forms['to_top']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['to_top']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE") ,$forms['to_top']['value'], array(
						'placeholder' => "", 
						'class' => 'tips', 
						'id' => $forms['to_top']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['pay_to_go_on_top']['key'], __('To top payment'), array('class'=>'control-label', 'for'=>$forms['pay_to_go_on_top']['key']))?>
					<div class="controls">
						<div class="input-append">
							<?= FORM::input($forms['pay_to_go_on_top']['key'], $forms['pay_to_go_on_top']['value'], array(
							'placeholder' => "", 
							'class' => 'tips', 
							'id' => $forms['pay_to_go_on_top']['key'],
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"),  
							));?> 
								<span class="add-on"><?=core::config('payment.paypal_currency')?></span>
						</div>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['pay_to_go_on_feature']['key'], __('To featured payment'), array('class'=>'control-label', 'for'=>$forms['pay_to_go_on_feature']['key']))?>
					<div class="controls">
						<div class="input-append">
						
							<?= FORM::input($forms['pay_to_go_on_feature']['key'], $forms['pay_to_go_on_feature']['value'], array(
							'placeholder' => "", 
							'class' => 'tips', 
							'id' => $forms['pay_to_go_on_feature']['key'],
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"),  
							));?> 
						
						<span class="add-on"><?=core::config('payment.paypal_currency')?></span></div>
					</div>
				</div>
				
				<div class="control-group">
					<?= FORM::label($forms['paypal_account']['key'], __('Paypal accout'), array('class'=>'control-label', 'for'=>$forms['paypal_account']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['paypal_account']['key'], $forms['paypal_account']['value'], array(
						'placeholder' => "", 
						'class' => 'tips', 
						'id' => $forms['paypal_account']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
						</div>
				</div>

				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
				</div>
			</fieldset>	
	</div><!--end span10-->
