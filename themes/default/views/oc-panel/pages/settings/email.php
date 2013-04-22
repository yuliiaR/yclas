<?php defined('SYSPATH') or die('No direct script access.');?>

	
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Email Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed general configuration values. Replace input fileds with new desired values')?></p>
		</div>

		<div class="well">
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
					<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>
				<div class="control-group">
					<?= FORM::label($forms['notify_email']['key'], __('Notify email'), array('class'=>'control-label', 'for'=>$forms['notify_email']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['notify_email']['key'], $forms['notify_email']['value'], array(
						'placeholder' => "youremail@mail.com", 
						'class' => 'tips', 
						'id' => $forms['notify_email']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['smtp_active']['key'], __('Smtp active'), array('class'=>'control-label', 'for'=>$forms['smtp_active']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['smtp_active']['key'], array(FALSE=>'FALSE',TRUE=>'TRUE'), $forms['smtp_active']['value'], array(
						'placeholder' => "TRUE or FALSE", 
						'class' => 'tips', 
						'id' => $forms['smtp_active']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),
						))?> 
					</div>
				</div>
                <div class="control-group">
                    <?= FORM::label($forms['smtp_ssl']['key'], __('Smtp ssl'), array('class'=>'control-label', 'for'=>$forms['smtp_ssl']['key']))?>
                    <div class="controls">
                        <?= FORM::select($forms['smtp_ssl']['key'], array(FALSE=>'FALSE',TRUE=>'TRUE'), $forms['smtp_ssl']['value'], array(
                        'placeholder' => "TRUE or FALSE", 
                        'class' => 'tips', 
                        'id' => $forms['smtp_ssl']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),                     
						))?> 
                    </div>
                </div>
				<div class="control-group">
					<?= FORM::label($forms['smtp_host']['key'], __('Smtp host'), array('class'=>'control-label', 'for'=>$forms['smtp_host']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['smtp_host']['key'], $forms['smtp_host']['value'], array(
						'placeholder' => '', 
						'class' => 'tips', 
						'id' => $forms['smtp_host']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),				
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['smtp_port']['key'], __('Smtp port'), array('class'=>'control-label', 'for'=>$forms['smtp_port']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['smtp_port']['key'], $forms['smtp_port']['value'], array(
						'placeholder' => "", 
						'class' => 'tips', 
						'id' => $forms['smtp_port']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),			
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['smtp_auth']['key'], __('Smtp auth'), array('class'=>'control-label', 'for'=>$forms['smtp_auth']['key']))?>
					<div class="controls">
							<?= FORM::select($forms['smtp_auth']['key'], array(FALSE=>'FALSE',TRUE=>'TRUE'), $forms['smtp_auth']['value'], array(
							'placeholder' => "", 
							'class' => 'tips', 
							'id' => $forms['smtp_auth']['key'], 
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"),					
							))?> 
						</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['smtp_user']['key'], __('Smtp user'), array('class'=>'control-label', 'for'=>$forms['smtp_user']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['smtp_user']['key'], $forms['smtp_user']['value'], array(
						'placeholder' => "", 
						'class' => 'tips', 
						'id' => $forms['smtp_user']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),				
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['smtp_pass']['key'], __('Smtp password'), array('class'=>'control-label', 'for'=>$forms['smtp_pass']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['smtp_pass']['key'], $forms['smtp_pass']['value'], array(
						'placeholder' => "",
						'type' => "password", 
						'class' => 'tips', 
						'id' => $forms['smtp_pass']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),				
						))?> 
					</div>
				</div>
				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email'))))?>
				</div>
			</fieldset>	
	</div><!--end span10-->
