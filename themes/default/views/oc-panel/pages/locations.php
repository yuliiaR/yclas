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
    <h1><?=__('Locations')?></h1>
    <p><?=__('Change the order of your locations, we don´t reccommend more than 2 level nested since probably won´t be displayed int he theme')?></p>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'create'))?>">
  <?=__('New location')?></a>
</div>

<ol class='plholder span8' id="ol_1" data-id="1">
<?=_('Home')?>
<?function lili($item, $key,$locs){?>
    <li data-id="<?=$key?>" id="li_<?=$key?>"><i class="icon-move"></i> <?=$locs[$key]['name']?>
        
        <a data-text="<?=__('Are you sure you want to delete? We will move the siblings Locations and ads to the parent of this location.')?>" 
           data-id="li_<?=$key?>" 
           class="btn btn-mini btn-danger index-delete pull-right" 
           title="<?=__('Delete')?>" 
           href="<?=Route::url('oc-panel', array('controller'=> 'location', 'action'=>'delete','id'=>$key))?>">
                    <i class="icon-trash icon-white"></i>
        </a>

        <a class="btn btn-mini btn-primary pull-right" 
            href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'update','id'=>$key))?>">
            <?=__('Edit')?>
        </a>

        <ol data-id="<?=$key?>" id="ol_<?=$key?>">
            <? if (is_array($item)) array_walk($item, 'lili', $locs);?>
        </ol><!--ol_<?=$key?>-->

    </li><!--li_<?=$key?>-->
<?}array_walk($order, 'lili',$locs);?>
</ol><!--ol_1-->

<span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'location','action'=>'saveorder'))?>'></span>