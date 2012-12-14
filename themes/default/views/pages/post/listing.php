<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row-fluid">

	<?=View::factory('sidebar')?>
	<div class="span10">
	 
	 <ul class="breadcrumb">
        	<li><a href="<?php ?>">Home</a> <span class="divider">&raquo;</span></li>
        	<li><a href="#">category</a> <span class="divider">&raquo;</span></li>
        	<li><a href="#">location</a> <span class="divider">&raquo;</span></li>
        	<li class="active">page 1</li>
	 </ul>
	  <?if(count($posts)):?>
	    <?foreach($posts as $post ):?>
	    <article class="list well clearfix">
	    	<h2>
	    	<a title="<?php echo $post->seotitle;?>" href="#"><?php echo $post->seotitle; ?></a></h2>
		     	
	    	</h2>
	    	
	    	<ul>
	    		<?php if ($post->published!=0){?>
		   			<li><b><?php _e('Publish Date');?>:</b> <?php echo $post->published;?></li>
		   		<?php }?>
		    	<?php if ($post->price!=0){?>
		    		<li class="price"><?php _e('Price');?>: <b><?php echo $post->price;?>&euro;</b></li>
		    	<?php }?>  
		    </ul>
		    <p><?php echo $post->description;?></p>
		    
		    <a title="<?php echo $post->seotitle;?>" href="#"><i class="icon-share"></i><?php _e('Read more')?></a>
	    	
	    	<?php //if(isset($_SESSION['admin'])){?>
	    		<br />
			<a href=""><?php _e("Edit");?></a> |
			<a onClick="" href=""><?php _e("Deactivate");?></a> |
			<a onClick="" href=""><?php _e("Spam");?></a> |
			<a onClick="" href=""><?php _e("Delete");?></a>

	    	<?php //}?>
	    </article>


	    <?endforeach?>
	    <?=$pagination?>
	  <?else:?>
	    	<h2><?=__('No items found')?></h2>
	  <?endif?>
	  
	</div><!--/span--> 
</div><!--/row-->