<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Advanced Search')?></h1>
</div>

<div class="well ">
	<?= FORM::open(Route::url('search'), array('class'=>'navbar-search form-horizontal', 'method'=>'GET', 'action'=>''))?>
	<fieldset>
	    <div class="form-group">
		    <div class="col-md-4">	
                <?= FORM::label('advertisement', __('Advertisement Title'), array('class'=>'control-label', 'for'=>'advertisement'))?>
		    	<input type="text" id="title" name="title" class="form-control" value="" placeholder="<?=__('Search')?>">
			</div>
		</div>

        <div class="form-group">
            <div class="col-md-4">          
                <?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category' ))?>
                <select name="category" id="category" class="form-control" >
                <option></option>
                <?function lili($item, $key,$cats){?>
                <?if(!core::config('advertisement.parent_category')):?>
                    <?if($cats[$key]['id_category_parent'] != 1):?>
                        <?if(!core::config('advertisement.parent_category')):?>
                    <?if($cats[$key]['id_category_parent'] != 1):?>
                        <option value="<?=$cats[$key]['seoname']?>"><?=$cats[$key]['name']?></option>
                    <?endif?>
                <?else:?>
                    <option value="<?=$cats[$key]['seoname']?>"><?=$cats[$key]['name']?></option>
                <?endif?>
                    <?endif?>
                <?else:?>
                    <?if(!core::config('advertisement.parent_category')):?>
                    <?if($cats[$key]['id_category_parent'] != 1):?>
                        <option value="<?=$cats[$key]['seoname']?>"><?=$cats[$key]['name']?></option>
                    <?endif?>
                <?else:?>
                    <option value="<?=$cats[$key]['seoname']?>"><?=$cats[$key]['name']?></option>
                <?endif?>
                <?endif?>
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
                <div class="col-md-4">          
                    <?= FORM::label('location', __('Location'), array('class'=>'form-label', 'for'=>'location' , 'multiple'))?>
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

			<?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('search')))?> 

	</fieldset>
	<?= FORM::close()?>
</div>


