<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Advansed Search')?></h1>
</div>
<div class="well advise clearfix">
	<?= FORM::open(Route::url('search'), array('class'=>'navbar-search pull-left', 'method'=>'GET', 'action'=>'','enctype'=>'multipart/form-data'))?>
	<fieldset>
	    <div class="control-group">
		    <?= FORM::label('advertisement', __('Advertisement'), array('class'=>'control-label', 'for'=>'advertisement'))?>
		    <div class="controls">	
		    	<input type="text" name="advert" class="" placeholder="<?=__('Search')?>">
			</div>
		</div>
		<div class="control-group">
			<?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category'))?>
			<div class="controls">	
			<?$val_category = array(''=>'');?>		
				<?foreach ($cat as $c):?>
					<? $val_category[$c->seoname] = $c->name; ?>
				<?endforeach?>		
				<?= FORM::select('category', $val_category, '',array('id'=>'category','class'=>'','name'=>"categ"));?>
				<p id="cat_price" class="text-warning"></p>
			</div>
		</div>
		<div class="control-group">
			<?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>
			<div class="controls">
			<?$val_location = array(''=>'');?>	
				<?foreach ($loc as $l):?>
					<?php $val_location[$l->seoname] = $l->name; ?>
				<?endforeach?>
				<?= FORM::select('location', $val_location, 0, array('id'=>'location', 'class'=>'','name'=>"locat") );?>
			
			</div>
		</div>
		<div class="form-actions">
			<?= FORM::button('submit', 'Search', array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('search')))?> 
		</div>
	</fieldset>
	<?= FORM::close()?>
</div>

