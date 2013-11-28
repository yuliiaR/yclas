<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="well advise clearfix">
    <h3><?=__('Advanced Search')?></h3>
    <?= FORM::open(Route::url('search'), array('class'=>'form-horizontal', 'method'=>'GET', 'action'=>''))?>
        <div class="form-group">    
            
            <div class="col-lg-4">
                <label class="control-label col-lg-2" for="search"><?=__('Search')?></label>
                <input type="text" id="search" name="title" class="form-control " value="<?=core::get('search')?>" placeholder="<?=__('Search')?>">  
            </div>
        </div>
        <!-- <div class="form-group">
            <label class="" for="price-min"><?=__('Price from')?> </label>  
            <input type="text" id="price-min" name="price-min" class="form-control" value="<?=core::get('price-min')?>" placeholder="0">
        </div>
        <div class="form-group">
            <label class="" for="price-max"><?=__('to')?></label>
            <input type="text" id="price-max" name="price-max" class="form-control" value="<?=core::get('price-max')?>" placeholder="100">
        </div> -->
        <div class="form-group"> 
            
            <div class="col-lg-4">
                <?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category'))?>
                <select name="category" id="category" class="form-control" >
                <option></option>
                <?function lili($item, $key,$cats){?>
                <option value="<?=$cats[$key]['seoname']?>" <?=(core::get('category')==$cats[$key]['seoname']?'selected':'')?> >
                    <?=$cats[$key]['name']?></option>
                    <?if (count($item)>0):?>
                    <optgroup label="<?=$cats[$key]['name']?>">    
                        <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
                        </optgroup>
                    <?endif?>
                <?}array_walk($order_categories, 'lili',$categories);?>
                </select>
            </div>
        </div>
        
        <?if(count($locations) !== 0):?>
            <div class="form-group">
                
                <div class="col-lg-4">   
                    <?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>  
                    <select name="location" id="location" class="form-control" >
                    <option></option>
                    <?function lolo($item, $key,$locs){?>
                    <option value="<?=$locs[$key]['seoname']?>"><?=$locs[$key]['name']?></option>
                        <?if (count($item)>0):?>
                        <optgroup label="<?=$locs[$key]['name']?>">    
                            <? if (is_array($item)) array_walk($item, 'lolo', $locs);?>
                            </optgroup>
                        <?endif?>
                    <?}array_walk($order_locations, 'lolo',$locations);?>
                    </select>
                </div>
            </div>
            
        <?endif?>
        <!-- Fields coming from custom fields feature -->
        <?if(isset($fields)):?>
        <?if (is_array($fields)):?>
            <?foreach($fields as $name=>$field):?>
            <div class="form-group">
            <?if($field['type'] == 'select' OR $field['type'] == 'radio') {
                $select = array(''=>'');
                foreach ($field['values'] as $select_name) {
                    $select[$select_name] = $select_name;
                }
            } else $select = $field['values']?>
                <?=Form::form_tag('cf_'.$name, array(    
                    'display'   => $field['type'],
                    'label'     => $field['label'],
                    'default'   => $field['values'],
                    'options'   => (!is_array($field['values']))? $field['values'] : $select,
                    'required'  => $field['required']))?> 
            </div>     
            <?endforeach?>
        <?endif?>
        <?endif?>
        <!-- /endcustom fields -->
        <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary pull-right', 'action'=>Route::url('search')))?> 

    <?= FORM::close()?>
</div>

<?if (count($ads)>0):?>
    <h3><?=__('Search results')?></h3>
    <?=View::factory('pages/ad/listing',array('pagination'=>$pagination,'ads'=>$ads,'category'=>NULL, 'location'=>NULL, 'user'=>$user))?>
<?endif?>