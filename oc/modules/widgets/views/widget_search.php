<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->subscribe_title?></h3>

<?= FORM::open(Route::url('search'), array('class'=>'navbar-search form-horizontal', 'method'=>'GET', 'action'=>'','enctype'=>'multipart/form-data'))?>
<!-- if categories on show selector of categories -->
    <div class="form-group">
        <div class="col-xs-10">  
            <?= FORM::label('advertisement', __('Advertisement Title'), array('class'=>'control-label', 'for'=>'advertisement'))?>
            <input type="text" id="title" name="title" class="form-control" value="" placeholder="<?=__('Search')?>">
        </div>
    </div>
<?if($widget->advanced != FALSE):?>
    <?if($widget->cat_items !== NULL):?>
        <div class="form-group">
            
            <div class="col-xs-10">
                <?= FORM::label('category', __('Categories'), array('class'=>'control-label', 'for'=>'category'))?>
                <select name="category" id="category" class="form-control">
                <option></option>
                <?function lili_search($item, $key,$cats){?>
                <?if ( count($item)==0 AND $cats[$key]['id_category_parent'] != 1):?>
                <option value="<?=$cats[$key]['seoname']?>"><?=$cats[$key]['name']?></option>
                <?endif?>
                    <?if ($cats[$key]['id_category_parent'] == 1 OR count($item)>0):?>
                    <option value="<?=$key?>"> <?=$cats[$key]['name']?> </option>  
                        <? if (is_array($item)) array_walk($item, 'lili_search', $cats);?>
                    <?endif?>
                <?}
                $cat_order = $widget->cat_order_items; 
                array_walk($cat_order , 'lili_search', $widget->cat_items);?>
                </select> 
            </div>
        </div>
    <?endif?>
<!-- end categories/ -->
<!-- locations -->
<?if($widget->loc_items !== NULL):?>
    <?if(count($widget->loc_items) > 1 AND core::config('advertisement.location') != FALSE):?>
        <div class="form-group">
            <div class="col-xs-10">
                <?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location' ))?>
                <select name="location" id="location" class="form-control">
                <option></option>
                <?function lolo_search($item, $key,$locs){?>
                <option value="<?=$locs[$key]['seoname']?>"><?=$locs[$key]['name']?></option>
                    <?if (count($item)>0):?>
                    <optgroup label="<?=$locs[$key]['name']?>_subscribe">    
                        <? if (is_array($item)) array_walk($item, 'lolo_search', $locs);?>
                        </optgroup>
                    <?endif?>
                <?}
                $loc_order = $widget->loc_order_items; 
                array_walk($loc_order , 'lolo_search',$widget->loc_items);?>
                </select>
            </div>
        </div>
    <?endif?>
<?endif?>
<?endif?>
<!-- Fields coming from custom fields feature -->

<?if($widget->custom != FALSE AND Theme::get('premium')==1 AND count($providers = Social::get_providers())>0):?>
    <?if (is_array($widget->custom_fields)):?>
        <?foreach($widget->custom_fields as $name=>$field):?>
        <?if($field['searchable']):?>
        <div class="form-group">
        
        <?if($field['type'] == 'select' OR $field['type'] == 'radio') {
            $select = array(''=>'');
            foreach ($field['values'] as $select_name) {
                $select[$select_name] = $select_name;
            }
        }?>
            <?=Form::form_tag('cf_'.$name, array(    
                'display'   => $field['type'],
                'label'     => $field['label'],
                'default'   => $field['values'],
                'options'   => (!is_array($field['values']))? $field['values'] : $select,
                'required'  => FALSE))?> 
        </div>
        <?endif?>     
        <?endforeach?>
    <?endif?>
<?endif?>
<!-- /endcustom fields -->

    <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('search')))?> 
<?= FORM::close()?>
