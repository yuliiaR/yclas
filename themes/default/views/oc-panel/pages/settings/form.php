<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10 well">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Form Configuration')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-info"><?=__('Here are listed only form fields that are optional. To activate/deactiave select "TRUE/FALSE" in desired field. ')?></p>
		</div>

		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
				<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>
				<div class="control-group">
					<?= FORM::label($forms['num_images']['key'], __('Number of images'), array('class'=>'control-label', 'for'=>$forms['num_images']['key']))?>
					<div class="controls">
						<?= FORM::input($forms['num_images']['key'], $forms['num_images']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['num_images']['key'], 
						))?> 
						<a title="" data-content="<?=__("Number of images")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Number of images")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['address']['key'], __('Address'), array('class'=>'control-label', 'for'=>$forms['address']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['address']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['address']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['address']['key'], 
						))?> 
						<a title="" data-content="<?=__("Address")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Address")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['phone']['key'], __('Phone'), array('class'=>'control-label', 'for'=>$forms['phone']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['phone']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['phone']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['phone']['key'], 
						))?> 
						<a title="" data-content="<?=__("Phone")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Phone")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['website']['key'], __('Website'), array('class'=>'control-label', 'for'=>$forms['website']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['website']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['website']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['website']['key'], 
						))?> 
						<a title="" data-content="<?=__("Website")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Website")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['location']['key'], __('Location'), array('class'=>'control-label', 'for'=>$forms['location']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['location']['key'],array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['location']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['location']['key'], 
						))?> 
						<a title="" data-content="<?=__("Location")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Location")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['price']['key'], __('Price'), array('class'=>'control-label', 'for'=>$forms['price']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['price']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['price']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['price']['key'], 
						))?> 
						<a title="" data-content="<?=__("Price")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Price")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['upload_file']['key'], __('Upload file'), array('class'=>'control-label', 'for'=>$forms['upload_file']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['upload_file']['key'],array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['upload_file']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['upload_file']['key'], 
						))?> 
						<a title="" data-content="<?=__("Upload file")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Upload file")?>">?</a>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label($forms['captcha-captcha']['key'], __('Captcha'), array('class'=>'control-label', 'for'=>$forms['captcha-captcha']['key']))?>
					<div class="controls">
						<?= FORM::select($forms['captcha-captcha']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['captcha-captcha']['value'], array(
						'placeholder' => "http://foo.com/", 
						'class' => 'input-xlarge', 
						'id' => $forms['captcha-captcha']['key'], 
						))?> 
						<a title="" data-content="<?=__("Captcha")?>" data-trigger="hover" data-placement="right" data-toggle="popover" class="tips" href="#" data-original-title="<?=__("Captcha")?>">?</a>
					</div>
				</div>
				<div class="form-actions">
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
				</div>
			</fieldset>
		<?= FORM::close()?>
	</div><!--end span 10-->
</div><!--end row-->