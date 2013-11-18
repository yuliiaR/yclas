<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Edit Custom Field')?></h1>
</div>
        
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'update','id'=>$name))?>">         
      <?=Form::errors()?>  
      
        <div class="form-group">
            <label class="control-label"><?=__('Name')?></label>
                <div class="col-sm-6">
                <input  DISABLED class="form-control" type="text" name="name" value="<?=$name?>" placeholder="<?=__('Name')?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label"><?=__('Type')?></label>
                <div class="col-sm-6">
                <input  DISABLED class="form-control" type="text" id="cf_type_field_input" name="type" value="<?=$field_data['type']?>" placeholder="<?=__('Type')?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label"><?=__('Label')?></label>
                <div class="col-sm-6">
                <input class="form-control" type="text" name="label" value="<?=$field_data['label']?>" placeholder="<?=__('Label')?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label"><?=__('Values')?></label>
                <div class="col-sm-6">
                <input class="form-control" type="text" id="cf_values_input" name="values" value="<?=(is_array($field_data['values']))? implode(",", $field_data['values']): $field_data['values']?>" placeholder="<?=__('Comma separated for select')?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <label class="checkbox">
                  <input type="checkbox" name="required" <?=($field_data['required']==TRUE)?'checked':''?>> 
                   <?=__('Required')?>
                </label>
            <div class="help-block"></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <label class="checkbox">
                  <input type="checkbox" name="searchable" <?=($field_data['searchable']==TRUE)?'checked':''?>> 
                   <?=__('Searchable')?>
                </label>
            <div class="help-block"></div></div>
        </div>
      
      <div class="form-actions">
        <a href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'index'))?>" class="btn btn-default"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
      </div>
</form>
