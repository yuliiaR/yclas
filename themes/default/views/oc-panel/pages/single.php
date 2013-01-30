<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	
	<div class="span10">
	<h1><?= $ad->title." ";?><?=__('Ad Page');?></h1>	    
	    <div>

	   

	    	<h3><?= $ad->title?></h3>
	    	<p><strong>Price : </strong> <?= $ad->price?></p>
		    <p><strong>description : </strong><?= $ad->description?></p>	
		    <p><strong>published: </strong> <?= $ad->published?></p>
		    <p><strong>Hits: </strong><?//echo $hits?></p>	    
	    </div>
	    
	</div><!--/span--> 
</div><!--/row-->