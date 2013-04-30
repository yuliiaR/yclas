<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>
<div class="page-header">
	<h1><?=__('Advertisements')?></h1>
</div>

<a class="btn btn-primary" href="<?=Route::url('post_new')?>" rel"tooltip" title="<?=__('New Advertisement')?>">
	<i class="icon-pencil icon-white"></i><?=__(' New')?>
</a>
<a class="btn btn-info" href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'moderate'))?>" rel"tooltip" title="<?=__('Not Published Advertisements')?>">
	<i class="icon-eye-open icon-white"></i><?=__(' Moderation')?>
</a>

<div id="advise" class="well advise clearfix">
	<p class="text-info">
        <?=__('General coonfigurations are here. Replace input fileds with new desired values')?>.
    </p>
</div>
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
				onclick="return confirm('<?=__('Deactivate?')?>');"
				rel"tooltip" title="<?=__('Deactivate')?>">
				<i class="icon-remove icon-white"></i>
			</a>
			<a class="delete btn btn-danger index-delete" 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete'))?>"
				onclick="col_selected();"
			    rel"tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
				<i class="icon-remove icon-white"></i>
			</a>
			<a class="featured btn btn-primary" 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'featured'))?>"
				onclick="col_selected();"
			    rel"tooltip" title="<?=__('Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it featured?')?>">
				<i class="icon-bookmark icon-white"></i>
			</a>
		</th>
	</tr>
	<? $i = 0; foreach($res as $ad):?>
	<tbody>
		<tr>
			<td>
				<label class="checkbox">
					<input type="checkbox" id="<?= $ad->id_ad.'_'?>" class="checkbox">
				</label>
			</td>
			<? foreach($category as $cat){ if ($cat->id_category == $ad->id_category) $cat_name = $cat->seoname; }?>
			<td><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"><?= $ad->title; ?></a>
			</td>

			<? foreach($category as $cat):?>
				<? if ($cat->id_category == $ad->id_category): ?>
					<td><?= $cat->name ?>
				<?endif?>
	    	<?endforeach?>
			
			<?php foreach($location as $loc):?>
				<? if ($loc->id_location == $ad->id_location): ?>
					<td><?= $loc->name?></td>
				<?endif?>
	    	<?endforeach?>
			<td><?= $hits[$i++];?></td>
			
			<? if($ad->status == 0):?>
				<td><?= __('Notpublished')?></td>
			<? elseif($ad->status == 1):?>
				<td><?= __('Published')?></td>
			<? elseif($ad->status == 30):?>
				<td><?= __('Spam')?></td>
	    	<? elseif($ad->status == 50):?>
				<td><?= __('Unavailable')?></td>
			<?endif?>
	    	
	    	<td><?= substr($ad->created, 0, 11)?></td>
			<td>
				<a class="btn btn-primary" 
					href="<?=Route::url('default', array('controller'=>'ad','action'=>'update','id'=>$ad->id_ad))?>" 
					rel"tooltip" title="<?=__('Update')?>">
					<i class="icon-edit icon-white"></i>
				</a>
				<a class="btn btn-warning" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Spam?')?>');"
					rel"tooltip" title="<?=__('Spam')?>">
					<i class="icon-fire icon-white"></i>
				</a>
				<a class="btn btn-warning" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>');"
					rel"tooltip" title="<?=__('Deactivate')?>">
					<i class="icon-remove icon-white"></i>
				</a>
				<a class="btn btn-danger index-delete" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Delete?')?>');"
				    rel"tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
					<i class="icon-remove icon-white"></i>
				</a>
				<?$deact_featured = new Model_Ad($ad->id_ad);?>
				<?if($deact_featured->featured == NULL):?>
				<a class="btn btn-primary" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'featured','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Make featured?')?>');"
				    rel"tooltip" title="<?=__('Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it featured?')?>">
					<i class="icon-bookmark icon-white"></i>
				</a>
				<?else:?>
				<a class="btn btn-inverse" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'featured','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Deactivate featured?')?>');"
				    rel"tooltip" title="<?=__('Deactivate Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to deactivate featured advertisement?')?>">
					<i class="icon-bookmark icon-white"></i>
				</a>
				<?endif?>
			</td>
		</tr>
	<?endforeach?>
	</tbody>
</table>
	 <?=$pagination?>
