<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10 well">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Genral Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed general configuration values. Replace input fileds with new desired values')?></p>
		</div>
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
				<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>
				<div class="control-group">
					<?= FORM::label($forms['base_url']['key'], __('Base URL'), array('class'=>'control-label', 'for'=>$forms['base_url']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['base_url']['key'], $forms['base_url']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['base_url']['key'], 
						))?> 
						<a title="" data-content="<?=__("Base url")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['moderation']['key'], __('Moderation'), array('class'=>'control-label', 'for'=>$forms['moderation']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['moderation']['key'], array(0,1,2,3), $forms['moderation']['value'], array(
						'placeholder' => $forms['moderation']['value'], 
						'class' => 'input-xlarge', 
						'id' => $forms['moderation']['key'], 
						))?> 
						<a title="" data-content="<?=__("Base url")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['site_name']['key'], __('Site name'), array('class'=>'control-label', 'for'=>$forms['site_name']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['site_name']['key'], $forms['site_name']['value'], array(
						'placeholder' => 'Openclassifieds', 
						'class' => 'input-xlarge', 
						'id' => $forms['site_name']['key'], 
						))?> 
						<a title="" data-content="<?=__("Base url")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['global-currency']['key'], __('Global currency'), array('class'=>'control-label', 'for'=>$forms['global-currency']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['global-currency']['key'], $forms['global-currency']['value'], array(
						'placeholder' => "USD", 
						'class' => 'input-xlarge', 
						'id' => $forms['global-currency']['key'], 
						))?> 
						<a title="" data-content="<?=__("Base url")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['featured_timer']['key'], __('Featured timer'), array('class'=>'control-label', 'for'=>$forms['featured_timer']['key']))?>
					<div class="controls">
						<div class="input-append">
							<?= FORM::input($forms['featured_timer']['key'], $forms['featured_timer']['value'], array(
							'placeholder' => $forms['featured_timer']['value'], 
							'class' => 'input-xlarge', 
							'id' => "10", 
							));?>
							<span class="add-on"><?=__("Day")?></span>
						</div> 
						<a title="" data-content="<?=__("Base url")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Base URL")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['advertisements_per_page']['key'], __('Advertisements per page'), array('class'=>'control-label', 'for'=>$forms['advertisements_per_page']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['advertisements_per_page']['key'], $forms['advertisements_per_page']['value'], array(
						'placeholder' => "20", 
						'class' => 'input-xlarge', 
						'id' => $forms['advertisements_per_page']['key'], 
						))?> 
						<a title="" data-content="<?=__("Base url")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Locale")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['number_format']['key'], __('Number format'), array('class'=>'control-label', 'for'=>$forms['number_format']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['number_format']['key'], $forms['number_format']['value'], array(
						'placeholder' => "20", 
						'class' => 'input-xlarge', 
						'id' => $forms['number_format']['key'], 
						))?> 
						<a title="" data-content="<?=__("Number format")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Number format")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['date_format']['key'], __('Date format'), array('class'=>'control-label', 'for'=>$forms['date_format']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['date_format']['key'], $forms['date_format']['value'], array(
						'placeholder' => "d/m/Y", 
						'class' => 'input-xlarge', 
						'id' => $forms['date_format']['key'], 
						))?> 
						<a title="" data-content="<?=__("Date format")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Date format")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('locale', __('Locale'), array('class'=>'control-label', 'for'=>'locale'))?>
					<div class="controls">
						<?= FORM::input('locale', core::config('i18n.locale'), array(
						'placeholder' => "en_EN", 
						'class' => 'input-xlarge', 
						'id' => 'locale', 
						))?> 
						<a title="" data-content="<?=__("Locale")?>"  data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Locale")?>">?</a>
					</div>
				</div>
				
				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general'))))?>
				</div>
			</fieldset>	
	</div><!--end span10-->
</div><!--end row-fluid-->