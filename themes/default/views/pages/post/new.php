<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Publish new advertisement')?></h1>
		</div>
		<?= FORM::open(Route::url('oc-panel',array('directory'=>'panel','controller'=>'post','action'=>'index')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<div class="control-group">
					<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
					<div class="controls">
						<?= FORM::input('title', '', array('placeholder' => __('Title'), 'class' => 'input-xlarge', 'id' => 'title'))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images'))?>
					<div class="controls">
						<?$_val_category = array();?>
						<?foreach ($_cat as $key => $cat):?>
							<?php $_val_category[$key+1] = $cat->seoname; ?>
						<?endforeach?>
					<?= FORM::select('category', $_val_category, $_val_category[1], array('id'=>'category') );?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>
					<div class="controls">
						<?$_val_location = array();?>
						<?foreach ($_loc as $loc):?>
							<?php $_val_location[] = $loc->seoname; ?>
						<?endforeach?>
					<?= FORM::select('location', $_val_location, $_val_location[1], array('id'=>'location') );?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description'))?>
					<div class="controls">
						<?= FORM::textarea('description', '', array('class'=>'input-xxlarge', 'name'=>'description', 'id'=>'description', 'rows'=>15))?>
						
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images'))?>
					<div class="controls">
						<?// FORM::file('image1', array('class'=>'input-file', 'id'=>'fileInput1'))?>
						<input class="input-file" type="file" name="image1" id="fileImput1" />
					</div>
					<div class="controls">
						<?= FORM::file('image2', array('class'=>'input-file', 'id'=>'fileInput2'))?>
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
						<?= FORM::input('address', Request::current()->post('address'), array('class'=>'input-xlarge', 'id'=>'address', 'placeholder'=>__('Phone')))?>
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
						<?= FORM::input('name', Request::current()->post('name'), array('class'=>'input-xlarge', 'id'=>'name', 'placeholder'=>__('Name')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
					<div class="controls">
						<?= FORM::input('email', Request::current()->post('email'), array('class'=>'input-xlarge', 'id'=>'email', 'placeholder'=>__('Email')))?>
					</div>
				</div>
				<?endif?>
				<div class="form-actions">
					<?= FORM::button('name', 'Publish now', array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('oc-panel',array('directory'=>'panel','controller'=>'post','action'=>'index'))))?>
					<p class="help-block">Dynamic text, for free or pay XX€..</p>
				</div>
			</fieldset>
		<?= FORM::close()?>

	</div>
	<!--/span-->
</div>
<!--/row-->
