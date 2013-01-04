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
	    	<a title="<?php echo $ad->seotitle;?>" href="<?=Route::url('post', array('controller'=>'post','action'=>'view','seotitle'=>$ad->seotitle))?>"> <?php echo $ad->seotitle; ?></a></h2>
		     	
	    	</h2>
	    	
	    	<ul>
	    		<?php if ($ad->published!=0){?>
		   			<li><b><?php _e('Publish Date');?>:</b> <?php echo $ad->published;?></li>
		   		<?php }?>
		    	<?php if ($ad->price!=0){?>
		    		<li class="price"><?php _e('Price');?>: <b><?php echo $ad->price;?>&euro;</b></li>
		    	<?php }?>  
		    </ul>
		    <p><?php echo $ad->description;?></p>
		    
		    <a title="<?php echo $ad->seotitle;?>" href="<?=Route::url('post', array('controller'=>'post','action'=>'view','seotitle'=>$ad->seotitle))?>"><i class="icon-share"></i><?php _e('Read more')?></a>
	    	
	    	<?php //if(isset($_SESSION['admin'])){?>
	    		<br />
			<a href="<?=Route::url('post', array('controller'=>'post','action'=>'edit','seotitle'=>$ad->seotitle))?>"><?php _e("Edit");?></a> |
			<a onClick="" href=""><?php _e("Deactivate");?></a> |
			<a onClick="" href=""><?php _e("Spam");?></a> |
			<a onClick="" href=""><?php _e("Delete");?></a>

	    	<?php //}?>
	    </article>
	    <?=Alert::show()?>

	    <?endforeach?>

	    <?foreach($sidebarCat as $cat):?>
	    	<p>Category: <?= $cat->seoname?>, id_category: <?= $cat->id_category?></p>
	    <?endforeach?>

	    <?foreach($sidebarLoc as $loc):?>
	    	<p>Location: <?= $loc->seoname?>, id location: <?= $loc->id_location?></p>
	    <?endforeach?>
	    
	    <?=$pagination?>

	    
	  <?else:?>
	    	<h2><?=__('No items found')?></h2>
	  <?endif?>
	  
	</div><!--/span--> 
</div><!--/row-->