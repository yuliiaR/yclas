<?php defined('SYSPATH') or die('No direct script access.');?>

<style type="text/css">

    body.dragging, body.dragging * {
      cursor: move !important;
    }
    .dragged {
        position: absolute;
        top: 0;
        opacity: .5;
        z-index: 2000;
    }

    ol.plholder li{
        cursor: move !important;
        display: block;
        margin: 5px;
        padding: 5px;
        border: 1px solid #CCC;
        color: white;
        background: gray;
        width: 90%;
    }

    ol.plholder li.placeholder{
        position: relative;
        margin: 0;
        padding: 0;
        border: none;
    }

    ol.plholder li.placeholder:before {
        position: absolute;
        content: "";
        width: 0;
        height: 0;
        margin-top: -5px;
        left: -5px;
        top: -4px;
        border: 5px solid transparent;
        border-left-color: red;
        border-right: none;
        color: red;
    }

</style>

<div class="page-header">
    <h1><?=__('Categories')?></h1>
    <p><?=__('Change the order of your categories, we don´t reccommend more than 2 level nested since probably won´t be displayed int he theme')?></p>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'create'))?>">
  <?=__('New category')?></a>
</div>

<ol class='plholder span8' id="ol_1" data-id="1">
<?=_('Home')?>
<?function lili($item, $key,$cats){?>
    <li data-id="<?=$key?>" id="li_<?=$key?>"><i class="icon-move"></i> <?=$cats[$key]['name']?>
        
        <a data-text="<?=__('Are you sure you want to delete? We will move the siblings categories and ads to the parent of this category.')?>" 
           data-id="li_<?=$key?>" 
           class="btn btn-mini btn-danger index-delete pull-right" 
           title="<?=__('Delete')?>" 
           href="<?=Route::url('oc-panel', array('controller'=> 'category', 'action'=>'delete','id'=>$key))?>">
                    <i class="icon-trash icon-white"></i>
        </a>

        <a class="btn btn-mini btn-primary pull-right" 
            href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'update','id'=>$key))?>">
            <?=__('Edit')?>
        </a>

        <ol data-id="<?=$key?>" id="ol_<?=$key?>">
            <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
        </ol><!--ol_<?=$key?>-->

    </li><!--li_<?=$key?>-->
<?}array_walk($order, 'lili',$cats);?>
</ol><!--ol_1-->

<span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'category','action'=>'saveorder'))?>'></span>