<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<?=View::factory('sidebar')?>
	<div class="span10">
		<?=Form::errors()?>
	<h1><?= $ad->seotitle;?></h1>	    
	    <div>
	    	<p><strong>Price : </strong> <?= $ad->price?></p>
		    <p><strong>description : </strong><?= $ad->description?></p>	
		    <p><strong>published: </strong> <?= $ad->created?></p>
		    <p><strong>Hits: </strong><?echo $hits?></p>	    
	    </div>
		 
		<?= FORM::open(Route::url('ad', array('seotitle'=>$ad->seotitle)), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
		<fieldset>
			<div class="control-group">
				<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
				<div class="controls ">
					<?= FORM::input('name', '', array('placeholder' => __('Name'), 'class' => 'input-xlarge', 'id' => 'name', 'required'))?>
				</div>
			</div>
			<div class="control-group">
				
				<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
				<div class="controls ">
					<?= FORM::input('email', '', array('placeholder' => __('Email'), 'class' => 'input-xlarge', 'id' => 'email', 'type'=>'email','required'))?>
				</div>
			</div>
			<div class="control-group">
				
				<?= FORM::label('subject', __('Subject'), array('class'=>'control-label', 'for'=>'subject'))?>
				<div class="controls ">
					<?= FORM::input('subject', "", array('placeholder' => __('Subject'), 'class' => 'input-xlarge', 'id' => 'subject'))?>
				</div>
			</div>
			<div class="control-group">
				<?= FORM::label('message', __('Message'), array('class'=>'control-label', 'for'=>'message'))?>
				<div class="controls">
					<?= FORM::textarea('message', "", array('class'=>'input-xlarge', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>7, 'required'))?>	
					</div>
			</div>
			<div class="control-group">
				<div class="controls">
					Captcha*:<br />
					<?php echo captcha::image_tag('contact');?><br />
					<?= FORM::input('captcha', "", array('class' => 'input-xlarge', 'id' => 'captcha', 'required'))?>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<?= FORM::button('submit', 'Contact Us', array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('ad', array('seotitle'=>$ad->seotitle))))?>
				</div>
				<br class="clear">
			</div>
		</fieldset>
		<?= FORM::close()?>	
	</div><!--/span--> 
</div><!--/row-->