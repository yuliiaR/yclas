<?php defined('SYSPATH') or die('No direct script access.');?>

	
<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Advertisement Configuration')?></h1>
        <p class="text-info"><?=__('Here are listed only form fields that are optional. To activate/deactiave select "TRUE/FALSE" in desired field. ')?></p>

</div>


<div class="well">
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
					'class' => 'tips', 
					'id' => $forms['num_images']['key'], 
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
				<?= FORM::label($forms['expire_date']['key'], __('Ad expiring date'), array('class'=>'control-label', 'for'=>$forms['expire_date']['key']))?>
				<div class="controls">
					<div class="input-append">
						<?= FORM::input($forms['expire_date']['key'], $forms['expire_date']['value'], array(
						'placeholder' => $forms['expire_date']['value'], 
						'class' => 'tips span2', 
						'id' => $forms['expire_date']['key'], 
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
				<?= FORM::label($forms['address']['key'], __('Address'), array('class'=>'control-label', 'for'=>$forms['address']['key']))?>
				<div class="controls">
					<?= FORM::select($forms['address']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['address']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips', 
					'id' => $forms['address']['key'], 
					'data-content'=> __("Thumb wid"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb wid"),
					))?> 
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label($forms['phone']['key'], __('Phone'), array('class'=>'control-label', 'for'=>$forms['phone']['key']))?>
				<div class="controls">
					<?= FORM::select($forms['phone']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['phone']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips', 
					'id' => $forms['phone']['key'], 
					'data-content'=> __("Thumb wid"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb wid"),
					))?> 
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label($forms['website']['key'], __('Website'), array('class'=>'control-label', 'for'=>$forms['website']['key']))?>
				<div class="controls">
					<?= FORM::select($forms['website']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['website']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips', 
					'id' => $forms['website']['key'], 
					'data-content'=> __("Thumb wid"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb wid"),
					))?> 
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label($forms['location']['key'], __('Location'), array('class'=>'control-label', 'for'=>$forms['location']['key']))?>
				<div class="controls">
					<?= FORM::select($forms['location']['key'],array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['location']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips', 
					'id' => $forms['location']['key'], 
					'data-content'=> __("Thumb wid"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb wid"),
					))?> 
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label($forms['price']['key'], __('Price'), array('class'=>'control-label', 'for'=>$forms['price']['key']))?>
				<div class="controls">
					<?= FORM::select($forms['price']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['price']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips', 
					'id' => $forms['price']['key'], 
					'data-content'=> __("Thumb wid"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb wid"),
					))?> 
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label($forms['upload_file']['key'], __('Upload file'), array('class'=>'control-label', 'for'=>$forms['upload_file']['key']))?>
				<div class="controls">
					<?= FORM::select($forms['upload_file']['key'],array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['upload_file']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips', 
					'id' => $forms['upload_file']['key'], 
					))?>
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label($forms['captcha']['key'], __('Captcha'), array('class'=>'control-label', 'for'=>$forms['captcha']['key']))?>
				<div class="controls">
					<?= FORM::select($forms['captcha']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['captcha']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips', 
					'id' => $forms['captcha']['key'], 
					'data-content'=> __("Thumb wid"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb wid"),
					))?> 
				</div>
			</div>
			<?$pages = array(''=>__('Deactivated'))?>
			<?foreach (Model_Content::get_pages() as $key => $value) {
				$pages[$value->seotitle] = $value->title;
			}?>
			<div class="control-group">
				<?= FORM::label($forms['tos']['key'], __('Terms of Service'), array('class'=>'control-label', 'for'=>$forms['tos']['key']))?>
				<div class="controls">
					<?= FORM::select($forms['tos']['key'], $pages, $forms['tos']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips', 
					'id' => $forms['tos']['key'], 
					'data-content'=> __("If you choose to use terms of service, you can select activate. And to edit content, select link 'Content' on your admin panel sidebar. Find page named 'Terms of service' click 'Edit'. In section 'Description' add content that suits you."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Terms of Service"),
					))?> 
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label($forms['disqus']['key'], __('Disqus'), array('class'=>'control-label', 'for'=>$forms['disqus']['key']))?>
				<div class="controls">
					<?= FORM::input($forms['disqus']['key'], $forms['disqus']['value'], array(
					'placeholder' => "", 
					'class' => 'tips', 
					'id' => $forms['disqus']['key'], 
					'data-content'=> __("Thumb wid"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb wid"),
					))?> 
				</div>
			</div>
			<div class="form-actions">
				<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
			</div>
		</fieldset>
	<?= FORM::close()?>
</div><!--end well-->
