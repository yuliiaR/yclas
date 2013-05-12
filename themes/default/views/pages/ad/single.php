<?php defined('SYSPATH') or die('No direct script access.');?>

	

	<?if ($ad->status != Model_Ad::STATUS_PUBLISHED && $permission === FALSE && ($ad->id_user != $user)):?>

	<div class="page-header">
		<h1><?= __("This advertisement doesn't exist, or is not yet published!")?></h1>
	</div>

	<?else:?>
	<?=Form::errors()?>
	<div class="page-header">
		<h1><?= $ad->title;?></h1>
	</div>

	<?$images = $ad->get_images()?>
	<?if($images):?>
	<div class="control-group">
		<div class="controls">
			<div id="gallery" data-toggle="modal-gallery" data-target="#modal-gallery" data-selector="a.gallery-item">
			<ul class="thumbnails">
				<?foreach ($images as $path => $value):?>
				<?if(isset($value['thumb'])):?>
				<li>
					<a data-href="/<?= str_replace("thumb_", '', $value['thumb'])?>" class="thumbnail gallery-item" data-gallery="gallery">
						<img src="/<?= $value['thumb']?>"  class="img-rounded" alt="">
					</a>
				</li>
				<?endif?>	
				<?endforeach?>
			</ul>
			</div>
		</div>	
	</div>

	<?endif?>
    	<p><strong><?=__('Price')?> : </strong> <?= $ad->price?></p>
	    <p><strong><?=__('Description')?> : </strong><?= Text::bb2html($ad->description,TRUE)?></p>	
	    <p><strong><?=__('Published')?>: </strong> <?= Date::format($ad->published, core::config('general.date_format'))?></p>
	    <p><strong><?=__('Hits')?>: </strong><?=$hits?></p>	    

		<button class="btn btn-success"type="button" data-toggle="modal" data-target="#contact-modal"><?=__('Send Message')?></button>
		<div id="contact-modal" class="modal hide fade">
        	<div class="modal-header">
         		<a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
				<h3><?=__('Contact')?></h3>
        	</div>
        
            <div class="modal-body">
				
					<?=Form::errors()?>
					
					<?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'user_contact', 'id'=>$ad->id_ad)), array('class'=>'form-horizontal well', 'enctype'=>'multipart/form-data'))?>
					<fieldset>
						<div class="control-group">
							<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
							<div class="controls ">
								<?= FORM::input('name', '', array('placeholder' => __('Name'), 'class' => '', 'id' => 'name', 'required'))?>
							</div>
						</div>
						<div class="control-group">
							
							<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
							<div class="controls ">
								<?= FORM::input('email', '', array('placeholder' => __('Email'), 'class' => '', 'id' => 'email', 'type'=>'email','required'))?>
							</div>
						</div>
						<div class="control-group">
							
							<?= FORM::label('subject', __('Subject'), array('class'=>'control-label', 'for'=>'subject'))?>
							<div class="controls ">
								<?= FORM::input('subject', "", array('placeholder' => __('Subject'), 'class' => '', 'id' => 'subject'))?>
							</div>
						</div>
						<div class="control-group">
							<?= FORM::label('message', __('Message'), array('class'=>'control-label', 'for'=>'message'))?>
							<div class="controls">
								<?= FORM::textarea('message', "", array('class'=>'', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>2, 'required'))?>	
								</div>
						</div>
						
						<?if (core::config('advertisement.captcha') != FALSE):?>
						<div class="control-group">
							<div class="controls">
								<?=__('Captcha')?>*:<br />
								<?=captcha::image_tag('contact')?><br />
								<?= FORM::input('captcha', "", array('class' => '', 'id' => 'captcha', 'required'))?>
							</div>
						</div>
						<?endif?>
  						
  						<div class="modal-footer">	
							<?= FORM::button('submit', 'Contact Us', array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'user_contact' , 'id'=>$ad->id_ad))))?>
						</div>
					</fieldset>
					<?= FORM::close()?>
    		</div>
		</div>

        <?if ($ad->address!==''):?>
        <iframe frameborder="0" noresize="noresize" 
            height="420px" width="100%" 
            src="<?=Route::url('map')?>?height=400&address=<?=$ad->address?>">
        </iframe>
        <?endif?>
	<?endif?>

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


