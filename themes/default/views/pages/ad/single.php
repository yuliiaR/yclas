<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">

	<div class="span9 well">
		<?if ($ad->status != 1 && $permission === FALSE):?>
		<div class="page-header">
			<h1><?= __('This advertisement does not exist, or is not yet published')?></h1>
		</div>
		<?else:?>
		<?=Form::errors()?>
		<div class="page-header">
			<h1><?= $ad->title;?></h1>
		</div>
		    
		<?php if($path):?>
		<div class="control-group">
			<div class="controls">
				<ul class="thumbnails">
					<?php foreach ($path as $path):?>
					<?$img_name = str_replace(".jpg", "", substr(strrchr($path, "/"), 1 ));?>
					<?if(strstr($path, '_') != '_original.jpg'):?>
					<li>
						<a href="" class="thumbnail">
							<img src="/<?echo $path?>" class="img-rounded" alt="">
						</a>	
					</li>
					<?endif?>
					<?endforeach?>
				</ul>
			</div>	
		</div>
		<?endif?>
	    	<p><strong>Price : </strong> <?= $ad->price?></p>
		    <p><strong>Description : </strong><?= Text::bb2html($ad->description,TRUE)?></p>	
		    <p><strong>Published: </strong> <?= $ad->created?></p>
		    <p><strong>Hits: </strong><?echo $hits?></p>	    
			
			<button class="btn btn-success"type="button" data-toggle="modal" data-target="#contact-modal">Send Message</button>
			<div id="contact-modal" class="modal hide fade">
            	<div class="modal-header">
             		<a class="close" data-dismiss="modal" >&times;</a>
              		<h3><?=__('Contact')?></h3>
            	</div>
            
	            <div class="modal-body">
					<?=View::factory('pages/contact', array('captcha_show' => $captcha_show))?>
	    		</div>
   			</div>
	
		<?endif?>
	</div><!--/span--> 
</div><!--/row-->