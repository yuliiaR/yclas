<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="well">

	<?if ($ad->status != 1 && $permission === FALSE):?>
	<div class="page-header">
		<h3><?= __('This advertisement doesn\'t exist, or is not yet published')?></h3>
	</div>
	<?else:?>
	<?=Form::errors()?>
	<div class="page-header">
		<h1><?= $ad->title;?></h1>
	</div>

	<?php if($path):?>
	<div class="control-group">
		<div class="controls">
			<div id="gallery" data-toggle="modal-gallery" data-target="#modal-gallery" data-selector="a.gallery-item">
			<ul class="thumbnails">
				<?php foreach ($path as $path):?>
				
				<?if(strstr($path, 'thumb') != FALSE):?>
				<li>
				
					<a data-href="/<?= str_replace("thumb_", '', $path)?>" class="thumbnail gallery-item" data-gallery="gallery">
						<img src="/<?= $path?>"  class="img-rounded" alt="">
					</a>	
				</li>
				<?endif?>
				<?endforeach?>
			</ul>
			</div>
		</div>	
	</div>

	<?endif?>
    	<p><strong>Price : </strong> <?= $ad->price?></p>
	    <p><strong>Description : </strong><?= Text::bb2html($ad->description,TRUE)?></p>	
	    <p><strong>Published: </strong> <?= Date::format($ad->published, core::config('general.date_format'))?></p>
	    <p><strong>Hits: </strong><?echo $hits?></p>	    
		
		<button class="btn btn-success"type="button" data-toggle="modal" data-target="#contact-modal">Send Message</button>
		<div id="contact-modal" class="modal hide fade">
        	<div class="modal-header">
         		<a class="close" data-dismiss="modal" >&times;</a>
        	</div>
        
            <div class="modal-body">
				<?=View::factory('pages/contact', array('captcha_show' => $captcha_show))?>
    		</div>
			</div>

	<?endif?>
	<!-- modal-gallery is the modal dialog used for the image gallery -->
	<div id="modal-gallery" class="modal modal-gallery hide fade" tabindex="-1">
	    <div class="modal-header">
	        <a class="close" data-dismiss="modal">&times;</a>
	        <h3 class="modal-title"></h3>
	    </div>
	    <div class="modal-body"><div class="modal-image"></div></div>
	    <div class="modal-footer">
	        <a class="btn btn-primary modal-next">Next <i class="icon-arrow-right icon-white"></i></a>
	        <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> Previous</a>
	        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> Slideshow</a>
	        <a class="btn modal-download" target="_blank"><i class="icon-download"></i> Download</a>
	    </div>
	</div>

    <?if ( strlen(core::config('advertisement.disqus'))>0 ):?>
    <hr>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = '<?=core::config('advertisement.disqus')?>'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
    <?endif?>

</div><!--/well--> 
