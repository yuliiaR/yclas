<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Publish new advertisement')?></h1>
		</div>
		<div id="advise" class="well advise clearfix">
			<p class="text-warning"> Price for this advertisement is: </p>
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
					<?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category'))?>
					<div class="controls">
						<?$_val_category = array('0'=>null);?>
						<?foreach ($_cat as $cat):?>
							<?php $_val_category[] = $cat->seoname; ?>
						<?endforeach?>
					<?= FORM::select('category', $_val_category, 0, array('id'=>'category','class'=>'input-xlarge', 'required') );?>
					<p id="cat_price" class="text-warning"></p>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>
					<div class="controls">
						<?$_val_location = array('0'=>NULL);?>
						<?foreach ($_loc as $loc):?>
							<?php $_val_location[] = $loc->seoname; ?>
						<?endforeach?>
					<?= FORM::select('location', $_val_location, 0, array('id'=>'location', 'class'=>'input-xlarge', 'required') );?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
					<div class="controls">
						<?= FORM::textarea('description', Request::current()->post('description'), array('class'=>'input-xxlarge', 'name'=>'description', 'id'=>'description', 'rows'=>15, 'required'))?>
						
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images1'))?>
					<div class="controls">
						<input class="input-file" type="file" name="image1" id="fileInput1" />
					</div>
					<?= FORM::label('images2', __('Images'), array('class'=>'control-label', 'for'=>'images2'))?>
					<div class="controls">	
						<input class="input-file" type="file" name="image2" id="fileInput2" />
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('file', __('File'), array('class'=>'control-label', 'for'=>'file1'))?>
					<div class="controls">
						<input class="input-file" type="file" name="file1" id="fileInput1" />
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('phone', __('Phone'), array('class'=>'control-label', 'for'=>'phone'))?>
					<div class="controls">
						<?= FORM::input('phone', Request::current()->post('phone'), array('class'=>'input-xlarge', 'id'=>'phone', 'placeholder'=>__('Phone')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('address', __('Address'), array('class'=>'control-label', 'for'=>'address'))?>
					<div class="controls">
						<?= FORM::input('address', Request::current()->post('address'), array('class'=>'input-xlarge', 'id'=>'address', 'placeholder'=>__('Address')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
					<div class="controls">
						<?= FORM::input('price', Request::current()->post('price'), array('placeholder' => __('Price'), 'class' => 'input-xlarge', 'id' => 'price', 'type'=>'number'))?>
					</div>
				</div>
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
				<?if ($captcha_show !== 'FALSE'):?>
				<div class="control-group">
					<div class="controls">
						Captcha*:<br />
						<?php echo captcha::image_tag('contact');?><br />
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
	<!--/span-->
</div>
<!--/row-->
