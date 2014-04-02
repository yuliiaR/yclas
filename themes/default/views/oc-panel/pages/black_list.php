<?php defined('SYSPATH') or die('No direct script access.');?>

<?//d(count($black_list ))?>
<h1><?=__('Black list')?></h1>
<table class="table table-striped">
	<thead>
        <tr>
          	<th><?=__('Name')?></th>
          	<th><?=__('Email')?></th>
          	<th><?=__('Action')?></th>
        </tr>
     </thead>
	<tbody>
		<?foreach ($black_list as $user):?>
        <tr>
          	<td><?=$user->name?></td>
          	<td><?=$user->email?></td>
          	<td><a href="<?=Route::url('oc-panel', array('controller'=>'pool','action'=>'remove','id'=>$user->id_user))?>" 
          		   class="btn btn-info">Remove</a></td>
        </tr>
        <?endforeach?>
    </tbody>
</table>
<?foreach ($black_list as $user):?>
	
<?endforeach?>

