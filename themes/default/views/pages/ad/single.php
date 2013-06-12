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
				<?if( isset($value['thumb']) AND isset($value['image']) ):?>
				<li>
					<a data-href="<?=URL::base('http')?><?= $value['image']?>" class="thumbnail gallery-item" data-gallery="gallery">
						<img src="<?=URL::base('http')?><?= $value['thumb']?>"  class="img-rounded" alt="">
					</a>
				</li>
				<?endif?>	
				<?endforeach?>
			</ul>
			</div>
		</div>	
	</div>
	<?endif?>

        <div class="well">
            <?if ($ad->price>0):?>
            <span class="label label-important"><?=money_format(core::config('general.number_format'), $ad->price)?></span>
            <?endif?>
            <a class="label" href="<?=Route::url('profile',  array('seoname'=>$ad->user->seoname))?>"><?=$ad->user->name?></a>
            <div class="pull-right">
                <span class="label label-info"><?= Date::format($ad->published, core::config('general.date_format'))?></span>
                <span class="label label-info"><?=$hits?> <?=__('Hits')?></span> 
            </div>    
        </div>

        <br>

        <div>
            <?= Text::bb2html($ad->description,TRUE)?>
            <?if (Valid::url($ad->website)):?>
            <p><a href="<?=$ad->website?>" rel="nofollow"><?=$ad->website?></a></p>
            <?endif?>
        </div>  
	    
        <?if ($ad->can_contact()):?>
		<button class="btn btn-success" type="button" data-toggle="modal" data-target="#contact-modal"><?=__('Send Message')?></button>
            <?if (core::config('advertisement.phone')==1 AND strlen($ad->phone)>1):?>
                <a class="btn btn-warning" href="tel:<?=$ad->phone?>"><?=$ad->phone?></a>
            <?endif?>
		<div id="contact-modal" class="modal hide fade">
        	<div class="modal-header">
         		<a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
				<h3><?=__('Contact')?></h3>
        	</div>
        
            <div class="modal-body">
				
					<?=Form::errors()?>
					
					<?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact', 'id'=>$ad->id_ad)), array('class'=>'form-horizontal well', 'enctype'=>'multipart/form-data'))?>
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
							<?= FORM::button('submit', 'Contact Us', array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact' , 'id'=>$ad->id_ad))))?>
						</div>
					</fieldset>
					<?= FORM::close()?>
    		</div>
		</div>
        <?endif?>

        <?=$ad->map()?>

	<?endif?>
	<!-- modal-gallery is the modal dialog used for the image gallery -->
	<div id="modal-gallery" class="modal modal-gallery hide fade" tabindex="-1">
	    <div class="modal-header">
	        <a class="close" data-dismiss="modal" >&times;</a>
	        <h3 class="modal-title"></h3>
	    </div>
	    <div class="modal-body"><div class="modal-image"></div></div>
	    <div class="modal-footer">
	        <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> <?=__('Previous')?></a>
	        <a class="btn btn-primary modal-next"><?=__('Next')?> <i class="icon-arrow-right icon-white"></i></a>
	        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> <?=__('Slideshow')?></a>
	        <a class="btn modal-download" target="_blank"><i class="icon-download"></i> <?=__('Download')?></a>
	    </div>
	</div>
    <?=$ad->disqus()?>