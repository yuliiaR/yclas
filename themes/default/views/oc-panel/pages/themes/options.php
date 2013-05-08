<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Theme Options')?></h1>
    <p><?=__('Here are listed specific theme configuration values. Replace input fileds with new desired values for theme')?> <code><?=Theme::$theme?></code></p>
</div>

<div class="well">
<?= FORM::open(Route::url('oc-panel',array('controller'=>'theme', 'action'=>'options')), array('class'=>'form-horizontal'))?>
    <fieldset>
        <?foreach ($options as $field => $attributes):?>
            <div class="control-group">
                <?=FORM::form_tag($field, $attributes, $data[$field])?>
            </div>
        <?endforeach?>
		<div class="form-actions">
			<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary',
             'action'=>Route::url('oc-panel',array('controller'=>'theme', 'action'=>'options'))))?>
		</div>
	</fieldset>	
<?=FORM::close()?>
</div><!--end span10-->
