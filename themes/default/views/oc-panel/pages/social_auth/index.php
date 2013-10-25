<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header pull-left">
    <h1><?=__('Social Authentication Settings')?></h1>

    <?= FORM::open(Route::url('oc-panel',array('controller'=>'social', 'action'=>'index')).'?define=cf', array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
        <fieldset>
            <?foreach ($config as $api => $options):?>
                <?var_dump($api)?>
                <div class="control-group">

                </div>
            <?endforeach?>
           
            <div class="form-actions">
                <?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn-small btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'social', 'action'=>'index')).'?define=cf'))?>
            </div>
        </fieldset>
    <?FORM::close()?>
</div>