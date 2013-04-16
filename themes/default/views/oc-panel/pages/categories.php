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


<ol class='plholder'>
<?
function lili($item, $key,$cats)
{
    ?>
        <li id="<?=$key?>"><i class="icon-move"></i>  <?=$cats[$key]['name']?>
            <button class="btn btn-primary btn-mini pull-right" data-toggle="modal" data-target="#modal_<?=$key?>" type="button"><?=__('Edit')?></button>
        <ol>
    <?
    if (is_array($item))
        array_walk($item, 'lili', $cats);
    echo '</ol></li>';
}

array_walk($order, 'lili',$cats);

?>
</ol>

<span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'category','action'=>'saveorder'))?>'></span>