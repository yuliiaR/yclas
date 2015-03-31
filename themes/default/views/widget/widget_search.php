<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->text_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <?= FORM::open(Route::url('search'), array('class'=>'form-horizontal', 'method'=>'GET', 'action'=>'','enctype'=>'multipart/form-data'))?>
        <!-- if categories on show selector of categories -->
        <div class="form-group">
            <div class="col-xs-12">  
                <?= FORM::label('advertisement', __('Advertisement Title'), array('class'=>'', 'for'=>'title'))?>
                <input type="text" id="title" name="title" class="form-control" value="" placeholder="<?=__('Search')?>">
            </div>
        </div>
        
        <?if($widget->advanced != FALSE):?>
            <?if($widget->cat_items !== NULL):?>
                <div class="form-group">
                    <div class="col-xs-12">
                        <?= FORM::label('category', __('Categories'), array('class'=>'', 'for'=>'category_widget_search'))?>
                        <select data-placeholder="<?=__('Categories')?>" name="category" id="category_widget_search" class="form-control disable-chosen">
                            <option value="<?=core::request('category')?>"></option>
                            <?function lili_search($item, $key,$cats){?>
                                <?if ( count($item)==0 AND $cats[$key]['id_category_parent'] != 1):?>
                                    <option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(core::request('category') == $cats[$key]['seoname'])?"selected":''?> ><?=$cats[$key]['name']?></option>
                                <?endif?>
                                    <?if ($cats[$key]['id_category_parent'] == 1 OR count($item)>0):?>
                                    <option value="<?=$key?>" data-id="<?=$cats[$key]['id']?>"> <?=$cats[$key]['name']?> </option>
                                        <optgroup label="<?=$cats[$key]['name']?>">  
                                        <? if (is_array($item)) array_walk($item, 'lili_search', $cats);?>
                                        </optgroup>
                                    <?endif?>
                            <?}
                            $cat_order = $widget->cat_order_items; 
                            if (is_array($cat_order))
                                array_walk($cat_order , 'lili_search', $widget->cat_items);?>
                        </select> 
                    </div>
                    <div class="col-xs-12">
                        <?= FORM::label('category', __('Categories'), array('class'=>'', 'for'=>'category_widget_search'))?>
                        <select data-placeholder="<?=__('Categories')?>" name="category" id="category_widget_search" class="form-control disable-chosen">
                            <option></option>
                            <?function lili_search($item, $key,$cats){?>
                            <option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(core::request('category') == $cats[$key]['seoname'])?"selected":''?> ><?=$cats[$key]['name']?></option>
                                <?if (count($item)>0):?>
                                    <optgroup label="<?=$cats[$key]['name']?>">  
                                    <? if (is_array($item)) array_walk($item, 'lili_search', $cats);?>
                                    </optgroup>
                                <?endif?>
                            <?}
                            $cat_order = $widget->cat_order_items; 
                            if (is_array($cat_order))
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
                        <div class="col-xs-12">
                            <?= FORM::label('location_widget_search', __('Locations'), array('class'=>'', 'for'=>'location_widget_search' ))?>
                            <select data-placeholder="<?=__('Locations')?>" name="location" id="location_widget_search" class="form-control disable-chosen">
                                <option></option>
                                <?function lolo_search($item, $key,$locs){?>
                                    <option value="<?=$locs[$key]['seoname']?>" <?=(core::request('location') == $locs[$key]['seoname'])?"selected":''?> ><?=$locs[$key]['name']?></option>
                                    <?if (count($item)>0):?>
                                        <optgroup label="<?=$locs[$key]['name']?>">    
                                            <? if (is_array($item)) array_walk($item, 'lolo_search', $locs);?>
                                        </optgroup>
                                    <?endif?>
                                <?}
                                $loc_order_search = $widget->loc_order_items; 
                                if (is_array($loc_order_search))
                                    array_walk($loc_order_search , 'lolo_search',$widget->loc_items);?>
                            </select>
                        </div>
                    </div>
                <?endif?>
            <?endif?>
    
            <?if(core::config('advertisement.price')):?>
                <div class="form-group">
                    <div class="col-xs-12"> 
                        <label class="" for="price-min"><?=__('Price from')?> </label>
                        <input type="text" id="price-min" name="price-min" class="form-control" value="<?=core::get('price-min')?>" placeholder="<?=__('Price from')?>">
                    </div>
                </div>
            
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="" for="price-max"><?=__('Price to')?></label>
                        <input type="text" id="price-max" name="price-max" class="form-control" value="<?=core::get('price-max')?>" placeholder="<?=__('to')?>">
                    </div>
                </div>
            <?endif?>
        <?endif?>
        <!-- Fields coming from custom fields feature -->
        <?if($widget->custom != FALSE AND Theme::get('premium')==1):?>
            <?if (is_array($widget->custom_fields)):?>
                <div id="widget-adv-cfs" style="position: absolute; left: -999em;">
                    <?$i=0; foreach($widget->custom_fields as $name=>$field):?>
                        <?if($field['searchable']):?>
                            <div class="form-group control-group" id="cf_search">
                                <div class="col-xs-12">
                                    <?if($field['type'] == 'select' OR $field['type'] == 'radio') {
                                        $select = array(''=>(!empty($field['label'])) ? $field['label']:$name);
                                        foreach ($field['values'] as $select_name) {
                                            $select[$select_name] = $select_name;
                                        }
                                    }?>
                                    <?if($field['type'] == 'checkbox' OR $field['type'] == 'radio'):?><div class="mt-10"></div><?endif?>
                                        <?=Form::cf_form_tag(   'cf_'.$name, array(    
                                                                'display'   => $field['type'],
                                                                'label'     => $field['label'],
                                                                'placeholder'     => (!empty($field['label'])) ? $field['label']:$name,
                                                                'data-placeholder' => $field['label'],
                                                                'categories'=> (isset($field['categories']))? $field['categories'] : "",
                                                                'tooltip'   => (isset($field['tooltip']))? $field['tooltip'] : "",
                                                                'options'   => (!is_array($field['values']))? $field['values'] : $select,
                                                                ),core::get('cf_'.$name),FALSE,TRUE)?> 
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?endif?>
                    <?$i++ ;endforeach?>
                </div>
            <?endif?>
        <?endif?>
        <!-- /endcustom fields -->
        <div class="clearfix"></div>
    
        <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary'))?> 
    <?= FORM::close()?>
</div>
