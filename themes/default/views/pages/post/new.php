<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		<div class="page-header">
			<h1><?=__('Publish new advertisement')?></h1>
		</div>
		<form class="form-horizontal" method="post" action="">
			<fieldset>
				<div class="control-group">
					<label class="control-label" for="title"><?=__('Title')?></label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="title" placeholder="<?=__('Title')?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="category"><?=__('Category')?></label>
					<div class="controls">
						<select id="category">
							<?foreach ($_cat as $cat):?>
								<option><? echo $cat->seoname;?></option>
							<?endforeach?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="location"><?=__('Location')?></label>
					<div class="controls">
						<select id="location">
							<?foreach ($_loc as $loc):?>
								<option><? echo $loc->seoname;?></option>
							<?endforeach?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="description"><?=__('Description')?></label>
					<div class="controls">
						<textarea class="input-xxlarge" name="description" id="description" rows="15"><?=Request::$current->post('description')?></textarea>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="fileInput1"><?=__('Images')?></label>
					<div class="controls">
						<input class="input-file" id="fileInput1" type="file">
					</div>
					<div class="controls">
						<input class="input-file" id="fileInput2" type="file">
					</div>
				</div>
				<?if (!Auth::instance()->get_user()):?>
				<div class="control-group">
					<label class="control-label" for="name"><?=__('Name')?></label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="name" placeholder="<?=__('Name')?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email"><?=__('Email')?></label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="email" placeholder="<?=__('Email')?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="phone"><?=__('Phone')?></label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="phone" placeholder="<?=__('Phone')?>">
					</div>
				</div>
				<?endif?>
				<div class="form-actions">
					<button type="submit" class="btn-large btn-primary"><?=__('Publish now')?></button>
					<p class="help-block">Dynamic text, for free or pay XX€..</p>
				</div>
			</fieldset>
		</form>

	</div>
	<!--/span-->
</div>
<!--/row-->
