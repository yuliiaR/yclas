<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Custom Fields')?></h1>
    <p><?=__('Advertisements Custom Fields')?></a></p>
    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'new'))?>">
  <?=__('New field')?></a>
</div>


<ol class='plholder span6' id="ol_1" data-id="1">
<?foreach($fields as $name=>$field):?>
    <li data-id="<?=$name?>" id="<?=$name?>"><i class="icon-move"></i> 
        <?=$name?>        
        <span class="label label-info "><?=$field['type']?></span>

        <a data-text="<?=__('Are you sure you want to delete? All data contained in this field will be deleted.')?>" 
           data-id="li_<?=$name?>" 
           class="btn btn-mini btn-danger index-delete pull-right"  
           href="<?=Route::url('oc-panel', array('controller'=> 'fields', 'action'=>'delete','id'=>$name))?>">
                    <i class="icon-trash icon-white"></i>
        </a>

        <a class="btn btn-mini btn-primary pull-right" 
            href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'update','id'=>$name))?>">
            <?=__('Edit')?>
        </a>



    </li>
<?endforeach?>
</ol><!--ol_1-->

<span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'saveorder'))?>'></span>