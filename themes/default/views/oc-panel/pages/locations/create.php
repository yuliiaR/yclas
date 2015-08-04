<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header" id="crud-<?=__($name)?>">
    <h1><?=__('New')?> <?=Text::ucfirst(__($name))?></h1>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Location details')?></h3>
            </div>
            <div class="panel-body">
                <?=$form->render()?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Find latitude & longitude')?></h3>
            </div>
            <div class="panel-body">
                <?= FORM::input('address', Request::current()->post('address'), array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Type address')))?>
                <div class="popin-map-container">
                    <div class="map-inner" id="map" 
                        data-lat="<?=core::config('advertisement.center_lat')?>" 
                        data-lon="<?=core::config('advertisement.center_lon')?>"
                        data-zoom="<?=core::config('advertisement.map_zoom')?>" 
                        style="height:200px;width:100%">
                    </div>
                </div>
                <ul class="list-inline">
                    <li><?=__('Latitude')?>: <span id="preview_lat">0</span></li>
                    <li><?=__('Longitude')?>: <span id="preview_lon">0</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>