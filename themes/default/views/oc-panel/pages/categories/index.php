<?php defined('SYSPATH') or die('No direct script access.');?>

<div id="page-categories" class="page-header">
    <ul class="list-inline pull-right">
        <li>
            <button type="button" class="btn btn-default quickgerator-toggle-panel">
                <?=__('Quick Generator')?>
            </button>
        </li>
        <li>
            <a class="btn btn-primary ajax-load create-toggle-panel" href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'create'))?>" title="<?=__('New Category')?>">
                <?=__('New Category')?>
            </a>
        </li>
    </ul>
    <h1><?=__('Categories')?></h1>
</div>
<p><?=__("Change the order of your categories. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).")." <a target='_blank' href='http://open-classifieds.com/2013/08/12/how-to-add-categories/'>".__('Read more')."</a>"?></p>

<ol class='plholder' id="ol_1" data-id="1">
    <?function lili($item, $key,$cats){?>
        <li data-id="<?=$key?>" id="li_<?=$key?>">
            <div class="drag-item">
                <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                <div class="drag-name">
                    <?=$cats[$key]['name']?>
                </div>
                <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                    href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'update','id'=>$key))?>">
                    <i class="fa fa-pencil-square-o"></i>
                </a>
                <a 
                    href="<?=Route::url('oc-panel', array('controller'=> 'category', 'action'=>'delete','id'=>$key))?>" 
                    class="drag-action index-delete" 
                    title="<?=__('Are you sure you want to delete?')?>" 
                    data-id="li_<?=$key?>" 
                    data-text="<?=__('We will move the siblings categories and ads to the parent of this category.')?>"
                    data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                    data-btnCancelLabel="<?=__('No way!')?>">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>
            </div>
    
            <ol data-id="<?=$key?>" id="ol_<?=$key?>">
                <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
            </ol><!--ol_<?=$key?>-->
    
        </li><!--li_<?=$key?>-->
    <?}
    if(is_array($order))
        array_walk($order, 'lili',$cats);?>
</ol><!--ol_1-->

<div 
    id="scotch-panel1"
    class="scotch-panel"
    data-containerSelector="body"
    data-direction="right"
    data-transition="ease"
    data-duration="300"
    data-clickSelector=".quickgerator-toggle-panel"
    data-distanceX="350px"
    data-enableEscapeKey="true"
>
    <div class="panel panel-offcanvas">
        <div class="panel-heading">
            <h3 class="panel-title"><?=__('Quick category creator')?></h3>
        </div>
        <div class="panel-body">
            <p><?=__('Add names for multiple categories, for each one push enter.')?></p>
            <?= FORM::open(Route::url('oc-panel',array('controller'=>'category','action'=>'multy_categories')), array('role'=>'form','enctype'=>'multipart/form-data'))?>
                <div class="form-group">
                    <?= FORM::label('multy_categories', __('Name'), array('for'=>'multy_categories'))?>
                    <?= FORM::input('multy_categories', '', array('placeholder' => __('Hit enter to confirm'), 'class' => 'form-control', 'id' => 'multy_categories', 'type' => 'text','data-role'=>'tagsinput'))?>
                </div>
                <?= FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'category','action'=>'multy_categories'))))?>
            <?= FORM::close()?>
        </div>
    </div>
</div>

<div 
    id="scotch-panel2"
    class="scotch-panel"
    data-containerSelector="body"
    data-direction="right"
    data-transition="ease"
    data-duration="300"
    data-clickSelector=".create-toggle-panel"
    data-distanceX="350px"
    data-enableEscapeKey="true"
>
    <div class="panel panel-offcanvas">
        <div class="panel-heading">
            <h3 class="panel-title"><?=__('Category settings')?></h3>
        </div>
        <div class="panel-body">
            <?=$form->render()?>
        </div>
    </div>
</div>