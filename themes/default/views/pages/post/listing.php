<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row-fluid">
	<?=View::factory('sidebar')?>
	<div class="span10">
	 
	 <ul class="breadcrumb">
        	<li><a href="#">Home</a> <span class="divider">&raquo;</span></li>
        	<li><a href="#">Category</a> <span class="divider">&raquo;</span></li>
        	<li><a href="#">Location</a> <span class="divider">&raquo;</span></li>
        	<li class="active">page 1</li>
	 </ul>
	  <?if(count($ads)):?>
	    <?foreach($ads as $ad ):?>
	   
	    <article class="list well clearfix">
	    	<h2>
	    	<a title="<?php echo $ad->title;?>" href="<?=Route::url('ad', array('controller'=>'ad','action'=>'view','seotitle'=>$ad->seotitle))?>"> <?php echo $ad->title; ?></a>
	    	</h2>
	    	<?if($img_path != NULL):?>
	    		 <img src="#" class="img-polaroid">
	    	<?endif?>
	    	<ul>
	    		<?php if ($ad->published!=0){?>
		   			<li><b><?php _e('Publish Date');?>:</b> <?php echo $ad->published;?></li>
		   		<?php }?>
		    	<?php if ($ad->price!=0){?>
		    		<li class="price"><?php _e('Price');?>: <b><?php echo $ad->price;?>&euro;</b></li>
		    	<?php }?>  
		    </ul>
		    <p><?php echo $ad->description;?></p>
		    
		    <a title="<?php echo $ad->seotitle;?>" href="<?=Route::url('ad', array('controller'=>'ad','action'=>'view','seotitle'=>$ad->seotitle))?>"><i class="icon-share"></i><?php _e('Read more')?></a>
	    
	    	<?if ($user !== NULL && $user->id_role == 10):?>
	    		<br />
			<a href="<?=Route::url('update', array('controller'=>'ad','action'=>'update','seotitle'=>$ad->seotitle,'id'=>$ad->id_ad))?>"><?php _e("Edit");?></a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad))?>"><?php _e("Deactivate");?></a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>"><?php _e("Spam");?></a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>"><?php _e("Delete");?></a>

			<?elseif($user !== NULL && $user->id_user == $ad->id_user):?>
				<br/>
			<a href="<?=Route::url('update', array('controller'=>'ad','action'=>'update','title'=>$ad->title,'id'=>$ad->id_ad))?>"><?php _e("Edit");?></a> 
			<?endif?>
	    </article>
	    <?=Alert::show()?>
	    <?endforeach?>

	    <?=$pagination?>	    
	  <?else:?>
	    	<h2><?=__('No items found')?></h2>
	  <?endif?>
	</div><!--/span--> 
</div><!--/row-->