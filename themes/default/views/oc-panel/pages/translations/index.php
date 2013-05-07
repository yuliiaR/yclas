<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Translations')?></h1>
    <p><?=__('Translations files available in the system')?></p>

    <a class="btn btn-primary pull-right" href="<?=Route::url('oc-panel',array('controller'=>'translations','action'=>'index'))?>?parse=1" >
        <?=__('Scan for strings')?></a>

</div>

<table class="table table-bordered">
    <tr>
        <th><?=__('Language')?></th>
    </tr>
<?foreach ($languages as $language):?>
    <tr>
        <td><?=$language?></td>
        <td width="5%">
            
            <a class="btn btn-warning" 
                href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'edit','id'=>$language))?>" 
                rel"tooltip" title="<?=__('Edit')?>">
                <i class="icon-pencil icon-white"></i>
            </a>

        </td>
        <td width="10%">
            <?if ($language!=$current_language):?>
            <a class="btn" 
                href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'index','id'=>$language))?>" 
                rel"tooltip" title="<?=__('Activate')?>">
                <?=__('Activate')?>
            </a>
            <?else:?>
                <span class="badge badge-info"><?=__('Active')?></span>
            <?endif?>
        </td>
    </tr>
<?endforeach?>
</table>