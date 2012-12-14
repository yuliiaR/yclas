<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<?=View::factory('sidebar')?>
	<div class="span10">
	<h1><?= $post->seotitle." ";?><?=__('Ad Page');?></h1>	    
	    <div>
	    	<h3><?= $post->seotitle?></h3>
	    	<p><strong>Price : </strong> <?= $post->price?></p>
		    <p><strong>description : </strong><?= $post->description?></p>	
		    <p><strong>published: </strong> <?= $post->published?></p>	    
	    </div>
	    
	</div><!--/span--> 
</div><!--/row-->