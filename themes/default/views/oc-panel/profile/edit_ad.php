<?php defined('SYSPATH') or die('No direct script access.');?>
	<div class="well">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Edit Advertisement')?></h1>
		</div>
		
		<?if((core::config('payment.pay_to_go_on_top') >= 0  
			&& core::config('payment.to_top') != FALSE )
			&& (core::config('payment.pay_to_go_on_feature') > 0 
			&& core::config('payment.to_featured') != FALSE)):?>
		<div id="advise" class="well advise clearfix">
			<?foreach ($extra_payment as $ex => $value) {
				if ($ex == 'pay_to_go_on_top') {
					$to_top = $value;
				} elseif ($ex == 'pay_to_go_on_feature'){
					$featured_price = $value; 
				} elseif ($ex == 'global-currency'){
					$global_currency = $value;
				} 
			}?>
			<?if(core::config('payment.to_top') != FALSE):?>
			<p class="text-info"><?=__('Your Advertisement can go on top again! For only '.$to_top.' '.core::config('general.global-currency'));?></p>
			<a class="btn btn-mini btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_top','controller'=>'ad','id'=>$ad->id_ad))?>">Go Top!</a>
			<?endif?>
			<?if(core::config('payment.to_featured') != FALSE):?>
			<p class="text-info"><?=__('Your Advertisement can go to featured! For only '.$featured_price.' '.core::config('general.global-currency'));?></p>
			<a class="btn btn-mini btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>">Go Featured!</a>
			<?endif?>
		</div>
		<?endif?>
		<?= FORM::open(Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad)), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<div class="control input-xxlarge">
					<?if(Auth::instance()->get_user()->id_role == 10):?>
					<? $owner = new Model_User($ad->id_user)?>
					<table class="table table-bordered ">
						<tr>
							<th><?=__('Id_User')?></th>
							<th><?=__('Profile')?></th>
							<th><?=__('Name')?></th>
							<th><?=__('Email')?></th>
						</tr>
						<tbody>
							<tr>
								<td><p><?= $ad->id_user?></p></td>
								<td>	
									<a href="<?=Route::url('profile', array('seoname'=>$owner->seoname))?>" alt=""><?= $owner->seoname?></a>
								</td>
								<td><p><?= $owner->name?></p></td>
								<td>	
									<a href="<?=Route::url('contact')?>"><?= $owner->email?></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="control-group">
					<?= FORM::label('status', __('Status'), array('class'=>'control-label', 'for'=>'status'))?>
					<div class="controls">
						<?php $status = array('0'=>__('notpublished'), '1'=>__('published'),'30'=>__('spam'),'50'=>__('unavailible'));?>
						<?= FORM::select('status', $status, $ad->status, array('id'=>'status','class'=>''));?>
					</div>
					<?endif?>
				</div>
				<div class="control-group">
					<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
					<div class="controls">
						<?= FORM::input('title', $ad->title, array('placeholder' => __('Title'), 'class' => '', 'id' => 'title', 'required'))?>
					</div>
				</div>
				<div class="control-group">
					<?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category'))?>
					<div class="controls">
					<?$_val_category = array();?>	
					<?php foreach($category as $cat): ?>
						<? $id = $cat->id_category; ?>
							<? $_val_category[$cat->id_category] = $cat->seoname; ?>
						<?endforeach?>
					<?= FORM::select('category', $_val_category, $ad->id_category, array('id'=>'category','class'=>'', 'required'));?>
					</div>
				</div>
				<?if(core::config('advertisement.location') != FALSE):?>
				<div class="control-group">
					<?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>
					<div class="controls">
						<?$_val_location = array();?>
						<?php foreach ($location as $loc):?>
							<? $_val_location[$loc->id_location] = $loc->seoname; ?>
						<?endforeach?>
					<?= FORM::select('location', $_val_location, $ad->id_location, array('id'=>'location', 'class'=>'', 'required'));?>
					</div>
				</div>
				<?endif?>
				<div class="control-group">
					<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description'))?>
					<div class="controls">
						<?= FORM::textarea('description', $ad->description, array('class'=>'span6', 'name'=>'description', 'id'=>'description', 'rows'=>8, 'required'))?>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<?php if($path):?>
						<ul class="thumbnails">
							<?php foreach ($path as $path):?>
							<?$img_name = str_replace(".jpg", "", substr(strrchr($path, "/"), 1 ));?>
							<?if(strstr($path, 'thumb') != FALSE): // only formated images (not originals)?>
							<li>
								<a class="thumbnail">
									<img src="/<?= $path?>" class="img-rounded" alt="">
								</a>
								
								<button class="btn btn-danger index-delete"
								   onclick="return confirm('<?=__('Delete?')?>');" 
								   type="submit" 
								   name="img_delete"
								   value="<?=$img_name?>" 
								   href="<?=Route::url('default', array('controller'=>'ad', 
								   									   'action'=>'img_delete', 
								   									   'id'=>$ad->id_ad))?>" 
								   rel"tooltip" 
								   title="<?=__('Delete image')?>">
									<?=__('Delete')?>
								</button>
							</li>
							<?endif?>
							<?endforeach?>
						</ul>
						<?endif?>
					</div>	
				</div>
				<div class="control-group">
					<?if ($perm !== FALSE):?>
						<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images0'))?>
						<div class="controls">
							<input class="input-file" type="file" name='image0' id='fileInput0' />
						</div>
					<?endif?>
				</div>
				<?if(core::config('advertisement.phone') != FALSE):?>
				<div class="control-group">
					<?= FORM::label('phone', __('Phone'), array('class'=>'control-label', 'for'=>'phone'))?>
					<div class="controls">
						<?= FORM::input('phone', $ad->phone, array('class'=>'', 'id'=>'phone', 'placeholder'=>__('Phone')))?>
					</div>
				</div>
				<?endif?>
				<?if(core::config('advertisement.address') != FALSE):?>
				<div class="control-group">
					<?= FORM::label('address', __('Address'), array('class'=>'control-label', 'for'=>'address'))?>
					<div class="controls">
						<?= FORM::input('address', $ad->address, array('class'=>'', 'id'=>'address', 'placeholder'=>__('Address')))?>
					</div>
				</div>
				<?endif?>
				<?if(core::config('advertisement.website') != FALSE):?>
				<div class="control-group">
					<?= FORM::label('website', __('Website'), array('class'=>'control-label', 'for'=>'website'))?>
					<div class="controls">
						<?= FORM::input('website', $ad->website, array('class'=>'', 'id'=>'website', 'placeholder'=>__('Website')))?>
					</div>
				</div>
				<?endif?>
				<?if(core::config('advertisement.price') != FALSE):?>
				<div class="control-group">
					<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on"><?=core::config('general.global-currency')?></span>
							<?= FORM::input('price', number_format($ad->price, 2), array('class' => '', 'id' => 'price', 'type'=>'number'))?>
						</div>
					</div>
				</div>
				<?endif?>
				<div class="form-actions">
					<?= FORM::button('submit', 'update', array('type'=>'submit', 'class'=>'btn-large btn-primary', 'action'=>Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))))?>
					<p class="help-block">Dynamic text, for free or pay XX€..</p>
				</div>
			</fieldset>
		<?= FORM::close()?>
	</div>
	<!--/well-->

