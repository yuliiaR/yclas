<?php defined('SYSPATH') or die('No direct script access.');?>
	<div class=" well">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Publish new advertisement')?></h1>
		</div>
		
		<?= FORM::open(Route::url('post_new',array('controller'=>'new','action'=>'index')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>

				<div class="control-group">
					<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
					<div class="controls">
						<?= FORM::input('title', Request::current()->post('title'), array('placeholder' => __('Title'), 'class' => 'input-xlarge', 'id' => 'title', 'required'))?>
					</div>
				</div>

				<div class="control-group">
					<?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category' , 'multiple'))?>
					<div class="controls">			
					
					
						<?foreach ($_cat as $cat):?>
							<?if($cat->seoname != 'all'):?>
							<? $_val_category[$cat->id_category] = $cat->name; ?>
							<?endif?>
						<?endforeach?>		
						<?= FORM::select('category', $_val_category, 0, array('id'=>'category','class'=>'input-xlarge', 'required') );?>
						<p id="cat_price" class="text-warning"></p>
					
					</div>
				</div>

				<?if($form_show['location'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>
					<div class="controls">
					
						<?foreach ($_loc as $loc):?>
							<?php $_val_location[$loc->id_location] = $loc->name; ?>
						<?endforeach?>
						<?= FORM::select('location', $_val_location, 0, array('id'=>'location', 'class'=>'input-xlarge', 'required') );?>
					
					</div>
				</div>
				<?endif?>

				<div class="control-group">
					<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
					<div class="controls">
						<?= FORM::textarea('description', Request::current()->post('description'), array('class'=>'span6', 'name'=>'description', 'id'=>'description' ,  'rows'=>10, 'required'))?>
						
					</div>
				</div>
				<div class="control-group">
					<?for ($i=0; $i < core::config("advertisement.num_images") ; $i++):?> 
						<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images'.$i))?>
						<div class="controls">
							<input class="input-file" type="file" name="<?='image'.$i?>" id="<?='fileInput'.$i?>" />
						</div>
					<?endfor?>
				</div>
				<?if($form_show['phone'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('phone', __('Phone'), array('class'=>'control-label', 'for'=>'phone'))?>
					<div class="controls">
						<?= FORM::input('phone', Request::current()->post('phone'), array('class'=>'input-xlarge', 'id'=>'phone', 'placeholder'=>__('Phone')))?>
					</div>
				</div>
				<?endif?>
				<?if($form_show['address'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('address', __('Address'), array('class'=>'control-label', 'for'=>'address'))?>
					<div class="controls">
						<?= FORM::input('address', Request::current()->post('address'), array('class'=>'input-xlarge', 'id'=>'address', 'placeholder'=>__('Address')))?>
					</div>
				</div>
				<?endif?>
				<?if($form_show['price'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
					<div class="controls">
						<div class="input-prepend">
						<span class="add-on"><?=core::config('general.global-currency')?></span>
						<?= FORM::input('price', Request::current()->post('price'), array('placeholder' => __('Price'), 'class' => 'input-large', 'id' => 'price', 'type'=>'number'))?>
						</div>
					</div>
				</div>
				<?endif?>
				<?if($form_show['website'] != FALSE):?>
				<div class="control-group">
					<?= FORM::label('website', __('Website'), array('class'=>'control-label', 'for'=>'website'))?>
					<div class="controls">
						<?= FORM::input('website', Request::current()->post('website'), array('placeholder' => __('Website'), 'class' => 'input-xlarge', 'id' => 'website'))?>
					</div>
				</div>
				<?endif?>
				<?if (!Auth::instance()->get_user()):?>
				<div class="control-group">
					<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
					<div class="controls">
						<?= FORM::input('name', Request::current()->post('name'), array('class'=>'input-xlarge', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
					<div class="controls">
						<?= FORM::input('email', Request::current()->post('email'), array('class'=>'input-xlarge', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
					</div>
				</div>
				<?endif?>
				<?if ($form_show['captcha'] != FALSE):?>
				<div class="control-group">
					<div class="controls">
						Captcha*:<br />
						<?= captcha::image_tag('contact');?><br />
						<?= FORM::input('captcha', "", array('class' => 'input-xlarge', 'id' => 'captcha', 'required'))?>
					</div>
				</div>
				<?endif?>
				<div class="form-actions">
					<?= FORM::button('submit', 'Publish now', array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('post_new',array('controller'=>'new','action'=>'index'))))?>
					<p class="help-block">Dynamic text, for free or pay XX€..</p>
				</div>
			</fieldset>
		<?= FORM::close()?>

	</div>
	<!--/well-->
