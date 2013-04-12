<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>
<h1><?=__('Moderation')?></h1>

<div class="">
	<a class="btn btn-primary" href="<?=Route::url('post_new')?>" rel"tooltip" title="<?=__('New Advertisement')?>">
		<i class="icon-pencil icon-white"></i><?=__(' New')?>
	</a>
	<a class="btn btn-info" href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'index'))?>" rel"tooltip" title="<?=__('Published Advertisements')?>">
		<i class="icon-eye-open icon-white"></i><?=__(' Ads')?>
	</a>
</div>
<? if($ads != NULL):?>
<table class="table table-bordered">
	<tr>
		<th>
			<label class="checkbox">
					<input type="checkbox" id="select-all" onclick="check_all();">
			</label>
		</th>
		<th><?=__('Name')?></th>
		<th><?=__('Category')?></th>
		<th><?=__('Location')?></th>
		<th><?=__('Hits')?></th>
		<th><?=__('Status')?></th>
		<th><?=__('Date')?></th>
		<th>
			<a class="spam btn btn-warning" 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam'))?>" 
				onclick="return confirm('<?=__('Spam?')?>');"
				rel"tooltip" title="<?=__('Spam')?>">
				<i class="icon-fire icon-white"></i>
			</a>
			<a class="deactivate btn btn-warning" 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate'))?>" 
				onclick="return confirm('<?=__('Deactivate?')?>'));"
				rel"tooltip" title="<?=__('Deactivate')?>">
				<i class="icon-remove icon-white"></i>
			</a>
			<a class="activate btn btn-warning" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate'))?>" 
					onclick="return confirm('<?=__('Make active?')?>');"
					rel"tooltip" title="<?=__('Activate')?>">
					<i class="icon-ok-sign icon-white"></i>
			</a>
			<a class="delete btn btn-danger index-delete" 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete'))?>"
				onclick="col_selected();"
			    rel"tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
				<i class="icon-remove icon-white"></i>
			</a>
		</th>
			
	</tr>
	
	<? $i = 0; foreach($ads as $ad):?>	
	<tbody>
		<tr>
			<td>
				<label class="checkbox">
					<input type="checkbox" id="<?echo $ad->id_ad.'_'?>" class="checkbox">
				</label>
			</td>
			<? foreach($category as $cat){ if ($cat->id_category == $ad->id_category) $cat_name = $cat->name; }?>
			<td><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"><? echo $ad->title; ?></a>
			</td>

			<? foreach($category as $cat):?>
				<? if ($cat->id_category == $ad->id_category): ?>
					<td><?php echo $cat->name ?>
				<?endif?>
	    	<?endforeach?>
			
			<?php foreach($location as $loc):?>
				<? if ($loc->id_location == $ad->id_location): ?>
					<td><?php echo $loc->name?></td>
				<?endif?>
	    	<?endforeach?>

			<td><? echo $hits[$i++];?></td>
			<? if($ad->status == 0):?>
				<td><? echo __('Notpublished')?></td>
			<? elseif($ad->status == 1):?>
				<td><? echo __('Published')?></td>
			<? elseif($ad->status == 30):?>
				<td><? echo __('Spam')?></td>
	    	<? elseif($ad->status == 50):?>
				<td><? echo __('Unavailable')?></td>
			<?endif?>
	    	<td><? echo substr($ad->created, 0, 11)?></td>
			<td>
				<a class="btn btn-primary" 
					href="<?=Route::url('default', array('controller'=>'ad','action'=>'update','id'=>$ad->id_ad))?>" 
					rel"tooltip" title="<?=__('Update')?>">
					<i class="icon-edit icon-white"></i>
				</a>
				<a class="btn btn-warning" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Make active?')?>');"
					rel"tooltip" title="<?=__('Activate')?>">
					<i class="icon-ok-sign icon-white"></i>
				</a>
				<a class="btn btn-danger index-delete" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Delete?')?>');"
				    rel"tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
					<i class="icon-remove icon-white"></i>
				</a>
			</td>
		</tr>
	<?endforeach?>
	</tbody>
</table>
	 <?=$pagination?>
<?else:?>
	<h2> <?=__("There are no unpublished Advertisements")?></h2>
<?endif?>
