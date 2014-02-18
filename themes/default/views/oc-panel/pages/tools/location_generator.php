<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('Interactive map locations generator')?></h1>
    <p><?=__('Reading map file')?><code> <?=$file?></code></p>
</div>
<p class="well">
    <span class="label label-info">Heads Up!</span> 
    <?=__('Use HTML code provided in map package, and create locations.html file in root folder')?>
</p>

<?= FORM::open(Route::url('oc-panel',array('controller'=>'tools','action'=>'location_generator')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
    <fieldset>
        <div class="form-actions">
        <?= FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'tools','action'=>'location_generator'))))?>
        </div>
    </fieldset>
<?= FORM::close()?>