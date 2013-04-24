<?php defined('SYSPATH') or die('No direct script access.');?>

	
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('General Configuration')?></h1>
		</div>
        <a class="btn pull-right" href="<?=Route::url('oc-panel',array('controller'=>'config'))?>"><?=__('All configs')?></a>.
		<div id="advise" class="well advise clearfix">
			<p class="text-info">
                <?=__('Published Advertisements are placed here. You can use settings to manage them.')?>.
            </p>
		</div>
		<div class="well">
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
					<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>
				<?foreach ($config_img as $c):?>
					<?$forms_img[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>
				<div class="control-group">
					<?= FORM::label($forms['base_url']['key'], __('Base URL'), array('class'=>'control-label', 'for'=>$forms['base_url']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['base_url']['key'], $forms['base_url']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'tips', 
						'id' => $forms['base_url']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['moderation']['key'], __('Moderation'), array('class'=>'control-label', 'for'=>$forms['moderation']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['moderation']['key'], array(0=>"Post directly",1=>"Moderation on",2=>"Payment on",3=>"Email confirmation on", 4=>"Email confirmation with Moderation", 5=>"Payment with Moderation"), $forms['moderation']['value'], array(
						'placeholder' => $forms['moderation']['value'], 
						'class' => 'tips ', 
						'id' => $forms['moderation']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['site_name']['key'], __('Site name'), array('class'=>'control-label', 'for'=>$forms['site_name']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['site_name']['key'], $forms['site_name']['value'], array(
						'placeholder' => 'Openclassifieds', 
						'class' => 'tips', 
						'id' => $forms['site_name']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['global-currency']['key'], __('Global currency'), array('class'=>'control-label', 'for'=>$forms['global-currency']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['global-currency']['key'], $forms['global-currency']['value'], array(
						'placeholder' => "USD", 
						'class' => 'tips', 
						'id' => $forms['global-currency']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['featured_timer']['key'], __('Featured timer'), array('class'=>'control-label', 'for'=>$forms['featured_timer']['key']))?>
					<div class="controls">
						<div class="input-append">
							<?= FORM::input($forms['featured_timer']['key'], $forms['featured_timer']['value'], array(
							'placeholder' => $forms['featured_timer']['value'], 
							'class' => 'tips span2', 
							'id' => $forms['featured_timer']['key'], 
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"),
							));?>
							<span class="add-on"><?=__("Days")?></span>
						</div> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['advertisements_per_page']['key'], __('Advertisements per page'), array('class'=>'control-label', 'for'=>$forms['advertisements_per_page']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['advertisements_per_page']['key'], $forms['advertisements_per_page']['value'], array(
						'placeholder' => "20", 
						'class' => 'tips', 
						'id' => $forms['advertisements_per_page']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['number_format']['key'], __('Number format'), array('class'=>'control-label', 'for'=>$forms['number_format']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['number_format']['key'], $forms['number_format']['value'], array(
						'placeholder' => "20", 
						'class' => 'tips', 
						'id' => $forms['number_format']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['date_format']['key'], __('Date format'), array('class'=>'control-label', 'for'=>$forms['date_format']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['date_format']['key'], $forms['date_format']['value'], array(
						'placeholder' => "d/m/Y", 
						'class' => 'tips', 
						'id' => $forms['date_format']['key'], 
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"),
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('locale', __('Locale'), array('class'=>'control-label', 'for'=>'locale'))?>
					<div class="controls">
						<?= FORM::input('locale', core::config('i18n.locale'), array(
						'placeholder' => "en_EN", 
						'class' => 'tips', 
						'id' => 'locale',
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms_img['allowed_formats']['key'], __('Allowed image formats'), array('class'=>'control-label', 'for'=>$forms_img['allowed_formats']['key']))?>
					<div class="controls">
						<?= FORM::select("allowed_formats[]", array('jpeg'=>'jpeg','jpg'=>'jpg','png'=>'png','raw'=>'raw','gif'=>'gif'), explode(',', $forms_img['allowed_formats']['value']), array(
						'placeholder' => $forms_img['allowed_formats']['value'],
						'multiple' => 'true',
						'class' => 'tips', 
						'id' => $forms_img['allowed_formats']['key'],
						'data-content'=> __("Thumb wid"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>__("Thumb wid"), 
						))?> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms_img['max_image_size']['key'], __('Max image size'), array('class'=>'control-label', 'for'=>$forms_img['max_image_size']['key']))?>
					<div class="controls">
						<div class="input-append">
							<?= FORM::input($forms_img['max_image_size']['key'], $forms_img['max_image_size']['value'], array(
							'placeholder' => "5", 
							'class' => 'tips span', 
							'id' => $forms_img['max_image_size']['key'],
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"), 
							))?>
							<span class="add-on"><?=__("px")?></span>
						</div> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms_img['height']['key'], __('Image height'), array('class'=>'control-label', 'for'=>$forms_img['height']['key']))?>
					<div class="controls">
						<div class="input-append">
							<?= FORM::input($forms_img['height']['key'], $forms_img['height']['value'], array(
							'placeholder' => "700", 
							'class' => 'tips', 
							'id' => $forms_img['height']['key'], 
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"),
							))?>
							<span class="add-on"><?=__("px")?></span>
						</div> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms_img['width']['key'], __('Image width'), array('class'=>'control-label', 'for'=>$forms_img['width']['key']))?>
					<div class="controls">
						<div class="input-append">
							<?= FORM::input($forms_img['width']['key'], $forms_img['width']['value'], array(
							'placeholder' => "1024", 
							'class' => 'tips', 
							'id' => $forms_img['width']['key'],
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"), 
							))?>
							<span class="add-on"><?=__("px")?></span>
						</div> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms_img['height_thumb']['key'], __('Thumb height'), array('class'=>'control-label', 'for'=>$forms_img['height_thumb']['key']))?>
					<div class="controls">
						<div class="input-append">
							<?= FORM::input($forms_img['height_thumb']['key'], $forms_img['height_thumb']['value'], array(
							'placeholder' => "200", 
							'class' => 'tips', 
							'id' => $forms_img['height_thumb']['key'],
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"), 
							))?>
							<span class="add-on"><?=__("px")?></span>
						</div> 
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms_img['width_thumb']['key'], __('Thumb width'), array('class'=>'control-label', 'for'=>$forms_img['width_thumb']['key']))?>
					<div class="controls">
						<div class="input-append">
							<?= FORM::input($forms_img['width_thumb']['key'], $forms_img['width_thumb']['value'], array(
							'placeholder' => "200", 
							'class' => 'tips', 
							'id' => $forms_img['width_thumb']['key'],
							'data-content'=> __("Thumb wid"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>__("Thumb wid"),

							))?>
							<span class="add-on"><?=__("px")?></span>
						</div> 
					</div>
				</div>
				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general'))))?>
				</div>
			</fieldset>	
	</div><!--end well-->
