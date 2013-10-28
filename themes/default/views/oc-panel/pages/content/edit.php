<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Emails')?></h1>
    
    <a class="btn btn-primary pull-right" href="http://open-classifieds.com/documentation/translate/"><?=__('New email')?></a>

</div>

<table class="table table-bordered">
    <tr>
        <th><?=__('email')?></th>
        <th><?=__('locale')?></th>
        <th><?=__('created')?></th>
        <th><?=__('status')?></th>
        <th></th>
    </tr>
<?foreach ($emails as $email):?>
 
    <tr>
        <td><?=$email->title?></td>
        <td><?=$email->locale?></td>
        <td><?=$email->created?></td>
        <td><?=$email->status?></td>
        <td width="5%">
            
            <a class="btn btn-primary" 
                href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'edit','id'=>$email))?>" 
                rel"tooltip" title="<?=__('Edit')?>">
                <i class="icon-edit icon-white"></i>
            </a>
            <a class="btn btn-danger" 
                href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'delete','id'=>$email))?>" 
                rel"tooltip" title="<?=__('Delete')?>">
                <i class="icon-trash icon-white"></i>
            </a>

        </td>
    </tr>
    
<?endforeach?>
</table>