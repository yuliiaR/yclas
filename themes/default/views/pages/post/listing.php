<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">

	<?=View::factory('sidebar')?>
	<div class="span10">
	 
	 <ul class="breadcrumb">
        	<li><a href="#">Home</a> <span class="divider">&raquo;</span></li>
        	<li><a href="#">category</a> <span class="divider">&raquo;</span></li>
        	<li><a href="#">location</a> <span class="divider">&raquo;</span></li>
        	<li class="active">page 1</li>
	 </ul>
	      		
	    <section>
	    	<header><h1>header text</h1></header>
	    	<details>category desc</details>
	    	<footer>link to publish a new one</footer>
	    </section>
	  
	  <?if(count($posts)):?>
	  
	    <?foreach($posts as $post):?>
	    <article>
	    	<header>
	    	header
	    	</header>
	    	<details>
	    	details
	    	</details>
	    	<footer>
	    	footer
	    	</footer>
	    </article>
	    <?endforeach?>
	    
	  <?else:?>
	    	<h2><?=__('No items found')?></h2>
	  <?endif?>
	  
	</div><!--/span--> 
</div><!--/row-->