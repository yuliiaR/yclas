<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'create'))?>" title="<?=__('New Location')?>">
        <?=__('New Location')?>
    </a>
    <h1><?=($location->id_location > 1) ? $location->name.' – ':NULL?> <?=__('Locations')?></h1>
    <p><?=__("Change the order of your locations. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).")?> <a href="http://open-classifieds.com/2013/08/22/how-to-add-locations/" target="_blank"><?=__('Read more')?></a></p>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading"><?=$location->name?></div>
            <div class="panel-body">
                <ol class='plholder' id="ol_<?=$location->id_location?>" data-id="<?=$location->id_location?>">
                <?foreach ($locs as $loc){?>
                
                    <li data-id="<?=$loc->id_location?>" id="li_<?=$loc->id_location?>"><i class="glyphicon glyphicon-move"></i> <?=$loc->name?>
                
                        <div class="btn-group pull-right">
                            <a class="btn btn-xs btn-info " title="<?=__('Browse childs')?>"
                                href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'index'))?>?id_location=<?=$loc->id_location?>">
                                <?=__('Browse childs')?>
                            </a>

                            <a class="btn btn-xs btn-primary ajax-load" title="<?=__('Edit')?>"
                                href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'update','id'=>$loc->id_location))?>">
                                <?=__('Edit')?>
                            </a>

                            <a 
                                href="<?=Route::url('oc-panel', array('controller'=> 'location', 'action'=>'delete','id'=>$loc->id_location))?>" 
                                class="btn btn-xs btn-danger index-delete index-delete-inline" 
                                title="<?=__('Are you sure you want to delete? We will move the siblings locations and ads to the parent of this location.')?>" 
                                data-id="li_<?=$loc->id_location?>" 
                                data-placement="left" 
                                data-href="<?=Route::url('oc-panel', array('controller'=> 'location', 'action'=>'delete','id'=>$loc->id_location))?>" 
                                data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                data-btnCancelLabel="<?=__('No way!')?>">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                        </div>

                    </li><!--li_<?=$loc->id_location?>-->
                
                <?}?>
                </ol><!--ol_1-->
                
                <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'location','action'=>'saveorder'))?>'></span>

            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <span class="label label-info"><?=__('Heads Up!')?> <?=__('Quick location creator.')?></span>
                        <p><?=__('Add names for multiple locations, for each one push enter.')?></p>
                      
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'multy_locations'.'?id_location='.Core::get('id_location', 1))), array('class'=>'form-horizontal', 'role'=>'form','enctype'=>'multipart/form-data'))?>
                            <div class="form-group">
                                <?= FORM::label('multy_locations', __('Name').':', array('class'=>'col-sm-3 control-label', 'for'=>'multy_locations'))?>
                                <div class="col-sm-8">
                                    <?= FORM::input('multy_locations', '', array('placeholder' => __('Hit enter to confirm'), 'class' => 'form-control', 'id' => 'multy_locations', 'type' => 'text','data-role'=>'tagsinput'))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <?= FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'location','action'=>'multy_locations'.'?id_location='.Core::get('id_location', 1)))))?>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'multy_locations'.'?id_location='.Core::get('id_location', 1))), array('id'=>'auto_locations_form', 'class'=>'form-horizontal', 'role'=>'form','enctype'=>'multipart/form-data'))?>
                            <div class="form-group" id="group-continent">
                                <label for="continent" class="col-sm-3 control-label" data-action="<?=__('Import continents')?>"><?=__('Continent')?></label>
                                <div class="col-sm-8">
                                    <select name="continent" id="continent" onchange="getPlaces(this.value,'country');" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-country">
                                <label for="country" class="col-sm-3 control-label" data-action="<?=__('Import countries')?>"><?=__('Country')?></label>
                                <div class="col-sm-8">
                                    <select name="country" id="country" onchange="getPlaces(this.value,'province');" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-province">
                                <label for="province" class="col-sm-3 control-label" data-action="<?=__('Import states/provinces')?>"><?=__('State')?> / <?=__('Province')?></label>
                                <div class="col-sm-8">
                                    <select name="province" id="province" onchange="getPlaces(this.value,'region');" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-region">
                                <label for="region" class="col-sm-3 control-label" data-action="<?=__('Import counties/regions')?>"><?=__('County')?> / <?=__('Region')?></label>
                                <div class="col-sm-8">
                                    <select name="region" id="region" onchange="getPlaces(this.value,'city');" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-city">
                                <label for="city" class="col-sm-3 control-label" data-action="<?=__('Import cities')?>"><?=__('City')?></label>
                                <div class="col-sm-8">
                                    <select name="city" id="city" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <input type="hidden" id="auto_locations" value="" name="multy_locations">
                                    <input type="hidden" id="auto_locations_lang" value="<?=substr(Core::config('i18n.locale'), 0, -3)?>" name="auto_locations_lang">
                                    <?= FORM::button('submit', __('Import'), array('type'=>'submit', 'class'=>'btn btn-primary', 'id'=>'auto_locations_import', 'action'=>Route::url('oc-panel',array('controller'=>'location','action'=>'multy_locations')).'?id_location='.Core::get('id_location', 1)))?>
                                    <?= FORM::button('reset', __('Reset'), array('type'=>'button', 'class'=>'btn btn-default', 'id'=>'auto_locations_import_reset'))?>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><?=_('Upload CSV file')?></div>
                    <div class="panel-body">
                        <p>
                            <span class="label label-info"><?=__('Heads Up!')?></span> <?=__('Select .csv file to upload locations. Valid format must be:')?>
                        </p>
                        <table class="table table-striped ">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Seoname</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Location 1</td>
                                    <td>location-seoname</td>
                                </tr>
                                <tr>
                                    <td>Location 2</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <p><?=__('Name is mandatory. But seoname is not, if not provided it is automatically generated')?></p>
                        <hr>
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool'.'?id_parent='.Core::get('id_location', 1))), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="csv_file_locations"><?=__('Import Locations')?></label>
                                <div class="col-sm-8">
                                    <input type="file" name="csv_file_locations" id="csv_file_locations" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <?= FORM::button('submit', __('Upload'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool'.'?id_parent='.Core::get('id_location', 1)))))?>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>