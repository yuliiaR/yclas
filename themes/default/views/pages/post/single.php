<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<?=View::factory('sidebar')?>
	<div class="span10">
	<h1><?= $ad->seotitle;?></h1>	    
	    <div>
	    	<p><strong>Price : </strong> <?= $ad->price?></p>
		    <p><strong>description : </strong><?= $ad->description?></p>	
		    <p><strong>published: </strong> <?= $ad->created?></p>
		    <p><strong>Hits: </strong><?echo $hits?></p>	    
	    </div>
	    
	</div><!--/span--> 
</div><!--/row-->