<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<table class="table table-bordered">
	<tr>
		<th><?=__('Name')?></th>
		<th><?=__('Category')?></th>
		<th><?=__('Location')?></th>
		<th><?=__('Hits')?></th>
		<th><?=__('Date')?></th>
		<th></th>
			
	</tr>
	
	<? $i = 0; foreach($res['ads'] as $p):?>	
	<tbody>
		<tr>
			<td><a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'view','id'=>$p->id_ad))?>"><? echo $p->title; ?></a>
			</td>

			<? foreach($category as $cat):?>
				<? if ($cat->id_category == $p->id_category): ?>
					<td><?php echo $cat->name ?>
				<?endif?>
	    	<?endforeach?>
			
			<?php foreach($location as $loc):?>
				<? if ($loc->id_location == $p->id_location): ?>
					<td><?php echo $loc->name?></td>
				<?endif?>
	    	<?endforeach?>
			<td><? echo $hits[$i++];?></td>
	    	<td><? echo substr($p->created, 0, 11)?></td>
			<td>
				<a class="btn btn-primary" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'update','id'=>$p->id_ad))?>" 
					rel"tooltip" title="<?=__('Update')?>">
					<i class="icon-edit icon-white"></i>
				</a>
				<a class="btn btn-warning" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$p->id_ad))?>" 
					onclick="return confirm('<?=__('Spam?')?>');"
					rel"tooltip" title="<?=__('Spam')?>">
					<i class="icon-fire icon-white"></i>
				</a>
				<a class="btn btn-warning" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$p->id_ad))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>'));"
					rel"tooltip" title="<?=__('Deactivate')?>">
					<i class="icon-remove icon-white"></i>
				</a>
				<a class="btn btn-danger index-delete" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$p->id_ad))?>" 
					onclick="return confirm('<?=__('Delete?')?>');"
				    rel"tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
					<i class="icon-remove icon-white"></i>
				</a>
			</td>
		</tr>
	<?endforeach?>
	</tbody>
</table>
	 <?=$res['pagination']?>
