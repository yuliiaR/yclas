<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<div class="span10">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Edit Advertisement')?></h1>
		</div>
		<?= FORM::open(Route::url('update',array('controller'=>'ad','action'=>'update', 'id'=>$ad->id_ad)), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<div class="control-group">
					<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
					<div class="controls">
						<?= FORM::input('title', $ad->title, array('placeholder' => __('Title'), 'class' => 'input-xlarge', 'id' => 'title', 'required'))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('categoty', __('Category'), array('class'=>'control-label', 'for'=>'category'))?>
					<div class="controls">
						<?//$_val_category = array('');?>
						<?//foreach ($_cat as $cat):?>
							<?//php $_val_category[] = $cat->seoname; ?>
						<?//endforeach?>
					<?//= FORM::select('category', $_val_category, $_val_category[1], array('id'=>'category','class'=>'input-xlarge', 'required') );?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>
					<div class="controls">
						<?$_val_location = array('');?>
						<?//foreach ($_loc as $loc):?>
							<?//php $_val_location[] = $loc->seoname; ?>
						<?//endforeach?>
					<?= FORM::select('location', $_val_location, $_val_location[0], array('id'=>'location', 'class'=>'input-xlarge',) );?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description'))?>
					<div class="controls">
						<?= FORM::textarea('description', $ad->description, array('class'=>'input-xxlarge', 'name'=>'description', 'id'=>'description', 'rows'=>15, 'required'))?>
						
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
						<?= FORM::input('phone', $ad->phone, array('class'=>'input-xlarge', 'id'=>'phone', 'placeholder'=>__('Phone')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('address', __('Address'), array('class'=>'control-label', 'for'=>'address'))?>
					<div class="controls">
						<?= FORM::input('address', $ad->phone, array('class'=>'input-xlarge', 'id'=>'address', 'placeholder'=>__('Address')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
					<div class="controls">
						<?= FORM::input('price', $ad->price, array('placeholder' => __('Price'), 'class' => 'input-xlarge', 'id' => 'price', 'type'=>'number'))?>
					</div>
				</div>
				<div class="form-actions">
					<?= FORM::button('submit', 'update', array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('update',array('controller'=>'ad','action'=>'update','id'=>$ad->id_ad))))?>
					<p class="help-block">Dynamic text, for free or pay XX€..</p>
				</div>
			</fieldset>
		<?= FORM::close()?>

	</div>
	<!--/span-->
</div>
<!--/row-->
