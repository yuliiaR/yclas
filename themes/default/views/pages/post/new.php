<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		<div class="page-header">
			<h1><?=__('Publish new advertisement')?></h1>
		</div>
		<?= FORM::open('new', array('class'=>'form-horizontal'))?>
			<fieldset>
				<div class="control-group">
					<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
					<div class="controls">
						<?= FORM::input('title', 'bla', array('placeholder' => __('Title'), 'class' => 'input-xlarge', 'id' => 'title'))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category'))?>
					<div class="controls">
						<?$_val_category = array();?>
						<?foreach ($_cat as $cat):?>
							<?php $_val_category[] = $cat->seoname; ?>
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
						<?= FORM::textarea('description', 'bla', array('class'=>'input-xxlarge', 'name'=>'description', 'id'=>'description', 'rows'=>15))?>
						<?=Request::$current->post('description')?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images'))?>
					<div class="controls">
						<?= FORM::file('image1', array('class'=>'input-file', 'id'=>'fileInput1'))?>
					</div>
					<div class="controls">
						<?= FORM::file('image2', array('class'=>'input-file', 'id'=>'fileInput2'))?>
					</div>
				</div>
				<?if (!Auth::instance()->get_user()):?>
				<div class="control-group">
					<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
					<div class="controls">
						<?= FORM::input('name', $user->name, array('class'=>'input-xlarge', 'id'=>'name', 'placeholder'=>__('Name')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
					<div class="controls">
						<?= FORM::input('email', $user->email, array('class'=>'input-xlarge', 'id'=>'email', 'placeholder'=>__('Email')))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('phone', __('Phone'), array('class'=>'control-label', 'for'=>'phone'))?>
					<div class="controls">
						<?= FORM::input('phone', 'bla', array('class'=>'input-xlarge', 'id'=>'phone', 'placeholder'=>__('Phone')))?>
					</div>
				</div>
				<?endif?>
				<div class="form-actions">
					<?= FORM::button('name', 'Publish now', array('type'=>'submit', 'class'=>'btn-large btn-primary'))?>
					<p class="help-block">Dynamic text, for free or pay XX€..</p>
				</div>
			</fieldset>
		<?= FORM::close()?>

	</div>
	<!--/span-->
</div>
<!--/row-->
