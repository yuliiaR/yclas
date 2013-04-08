<?php defined('SYSPATH') or die('No direct script access.');?>

        <h3>Categories</h3>
    	<ul class="thumbnails">

            <?foreach($ads as $ad):?>
              <?foreach ($categ as $cat):?>
                <?if($cat->id_category == $ad->id_category):?>
                    <?$cat_name = $cat->name;?>
                <?endif?>
            <?endforeach?>
            <li class="span3">
                <div class="thumbnail latest_ads" style="height: 300px; overflow: hidden;">
                    <?if($img_path[$ad->seotitle] != NULL):?>
                    <img src="/<?=$img_path[$ad->seotitle][1]?>" class="img-polaroid">
                    <?endif?>
                    <div class="caption">
                        <h5><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"><?=$ad->title?></a></h5>
                        <p ><?=$ad->description?></p>
                    </div>
                </div>
            </li>     
            <?endforeach?>
        </ul>
        

        <h3>Categories</h3>
        <ul class="thumbnails">
            <?foreach($categ as $c):?>
            <?if($c->id_category == $c->id_category_parent || $c->id_category_parent == 0 || $c->id_category_parent == NULL):?>
            <div class="span4">
                <div class="category_box_title">
                    <p><a title="<?=$c->name?>" href="<?=Route::url('sort_by', array('category'=>$c->name))?>"><?=strtoupper($c->name);?></a></p>
                </div>  
                <div class="well custom_box_content" style="padding: 8px 0;">
                    <ul class="nav nav-list">
                        <?foreach($children_categ as $chi):?>
                            <?if($chi['parent'] == $c->id_category):?>
                            <li><a title="<?=$chi['name']?>" href="<?=Route::url('sort_by', array('category'=>$chi['name']))?>"><?=$chi['name'];?> <span class="count_ads">( <?=$chi['count']?> )</span></a></li>
                            <?endif?>
                         <?endforeach?>
                    </ul>
                </div>
            </div>
            <?endif?>
            <?endforeach?>
        </ul>


