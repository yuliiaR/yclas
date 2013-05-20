<?php defined('SYSPATH') or die('No direct script access.');?>


 <?=Form::errors()?>
<div class="page-header">
	<h1><?=__('General Configuration')?></h1>
    <p class="">
        <?=__('General site settings.')?>
        <a class="btn pull-right" href="<?=Route::url('oc-panel',array('controller'=>'config'))?>"><?=__('All configurations')?></a>

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
            <?= FORM::label($forms['maintenance']['key'], __('Maintenance Mode'), array('class'=>'control-label', 'for'=>$forms['maintenance']['key']))?>
            <div class="controls">
                <?= FORM::select($forms['maintenance']['key'], array(FALSE=>'FALSE',TRUE=>'TRUE'), $forms['maintenance']['value'], array(
                'placeholder' => "TRUE or FALSE", 
                'class' => 'tips', 
                'id' => $forms['maintenance']['key'], 
                'data-content'=> __("Enables the site to maintenance"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Maintenance Mode"),
                ))?> 
            </div>
        </div>

        <div class="control-group">
            <?= FORM::label($forms['site_name']['key'], __('Site name'), array('class'=>'control-label', 'for'=>$forms['site_name']['key']))?>
            <div class="controls">
                <?= FORM::input($forms['site_name']['key'], $forms['site_name']['value'], array(
                'placeholder' => 'Open-classifieds', 
                'class' => 'tips', 
                'id' => $forms['site_name']['key'],
                'data-content'=> __("Here you can declare your display name. This is seen by everyone!"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Site Name"), 
                ))?> 
            </div>
        </div>
		<div class="control-group">
			<?= FORM::label($forms['base_url']['key'], __('Base URL'), array('class'=>'control-label', 'for'=>$forms['base_url']['key']))?>
			<div class="controls">
				<?= FORM::input($forms['base_url']['key'], $forms['base_url']['value'], array(
				'placeholder' => "http://foo.com/", 
				'class' => 'tips', 
				'id' => $forms['base_url']['key'],
				'data-content'=> __("Is a base path of your site (e.g. http://open-classifieds.com/ ). Everything else is built based on this filed."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Base URL path"), 
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
				'data-content'=> __("Moderation is how you control newly created advertisements. You can set it up to fulfill your needs. For example, 'Post directly' will enable new ads to be posted directly, and get published as soon they submit."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Moderation controls"), 
				))?> 
			</div>
		</div>
		<div class="control-group">
			<?= FORM::label($forms['global_currency']['key'], __('Global currency'), array('class'=>'control-label', 'for'=>$forms['global_currency']['key']))?>
			<div class="controls">
				<?= FORM::input($forms['global_currency']['key'], $forms['global_currency']['value'], array(
				'placeholder' => "USD", 
				'class' => 'tips', 
				'id' => $forms['global_currency']['key'],
				'data-content'=> __("Global currency is country specific. There are no restrictions, this is only to declare currency for advertisements. Note: This is different from payment currencies. "),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Country specific currency"),
				))?> 
			</div>
		</div>
		<div class="control-group">
			<?= FORM::label($forms['advertisements_per_page']['key'], __('Advertisements per page'), array('class'=>'control-label', 'for'=>$forms['advertisements_per_page']['key']))?>
			<div class="controls">
				<?= FORM::input($forms['advertisements_per_page']['key'], $forms['advertisements_per_page']['value'], array(
				'placeholder' => "20", 
				'class' => 'tips', 
				'id' => $forms['advertisements_per_page']['key'], 
				'data-content'=> __("This is to control how many advertisements are being displayed per page. Insert an integer value, as a number limit."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Number of ads per page"),
				))?> 
			</div>
		</div>
		<div class="control-group">
           <?= FORM::label($forms['feed_elements']['key'], __('Advertisements in RSS'), array('class'=>'control-label', 'for'=>$forms['feed_elements']['key']))?>
           <div class="controls">
               <?= FORM::input($forms['feed_elements']['key'], $forms['feed_elements']['value'], array(
               'placeholder' => "20", 
               'class' => 'tips', 
               'id' => $forms['feed_elements']['key'], 
               'data-content'=> __("Number of Ads"),
               'data-trigger'=>"hover",
               'data-placement'=>"right",
               'data-toggle'=>"popover",
               'data-original-title'=>__("How many ads are going to appear in the RSS of your site."),
               ))?> 
           </div>
       </div>
       <div class="control-group">
           <?= FORM::label($forms['map_elements']['key'], __('Advertisements in Map'), array('class'=>'control-label', 'for'=>$forms['map_elements']['key']))?>
           <div class="controls">
               <?= FORM::input($forms['map_elements']['key'], $forms['map_elements']['value'], array(
               'placeholder' => "20", 
               'class' => 'tips', 
               'id' => $forms['map_elements']['key'], 
               'data-content'=> __("Number of Ads"),
               'data-trigger'=>"hover",
               'data-placement'=>"right",
               'data-toggle'=>"popover",
               'data-original-title'=>__("How many ads are going to appear in the map of your site."),
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
				'data-content'=> __("Number format is how you want to display numbers related to advertisements. More specific advertisement price. Every country have a specific way of dealing with decimal digits."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Decimal representation"), 
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
				'data-content'=> __("Each advertisement have publish date. By selecting format, you can change how is shown on your website."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Date format"),
				))?> 
			</div>
		</div>
		<div class="control-group">
			<?= FORM::label($forms['analytics']['key'], __('Analytics'), array('class'=>'control-label', 'for'=>$forms['analytics']['key']))?>
			<div class="controls">
				<?= FORM::input($forms['analytics']['key'], $forms['analytics']['value'], array(
				'placeholder' => 'UA-XXXXX', 
				'class' => 'tips', 
				'id' => $forms['analytics']['key'],
				'data-content'=> __(""),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Analytics"), 
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
				'data-content'=> __("Set this up to restrict image formats that are being uploaded to your server."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Allowed image formats"), 
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
					'data-content'=> __("Control the size of images being uploaded. Enter an integer value to set maximum image size in mega bites(Mb)."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image size in mega bites(Mb)"), 
					))?>
					<span class="add-on">MB</span>
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
					'data-content'=> __("Each image is resized when uploaded. This is the height of big image. Note: you can leave this field blank to set AUTO height resize."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image height in pixels(px)"),
					))?>
					<span class="add-on">px</span>
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
					'data-content'=> __("Each image is resized when uploaded. This is the width of big image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image width in pixels(px)"), 
					))?>
					<span class="add-on">px</span>
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
					'data-content'=> __("Thumb is a small image resized to fit certain elements. This is height of this image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb height in pixels(px)"), 
					))?>
					<span class="add-on">px</span>
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
					'data-content'=> __("Thumb is a small image resized to fit certain elements. This is width of this image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb width in pixels(px)"),

					))?>
					<span class="add-on">px</span>
				</div> 
			</div>
		</div>
        <div class="control-group">
            <?= FORM::label($forms_img['quality']['key'], __('Image quality'), array('class'=>'control-label', 'for'=>$forms_img['quality']['key']))?>
            <div class="controls">
                <div class="input-append">
                    <?= FORM::input($forms_img['quality']['key'], $forms_img['quality']['value'], array(
                    'placeholder' => "95", 
                    'class' => 'tips', 
                    'id' => $forms_img['quality']['key'],
                    'data-content'=> __("Choose the quality of the stored images (1-100% of the original)."),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("Image Quality)"),

                    ))?>
                    <span class="add-on">%</span>
                </div> 
            </div>
        </div>
		<div class="form-actions">
			<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general'))))?>
		</div>
	</fieldset>	
</div><!--end well-->
