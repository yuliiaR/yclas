<?php defined('SYSPATH') or die('No direct script access.');?>
	 

	<div class="well advise clearfix" id="advise">
		<p><?=__('Apartments, flats, monthly rentals, long terms, for days... this is the section to have your apartment!')?></p>
		<i class="icon-pencil"></i> <a title="New Advertisement" href="<?=Route::url('post_new')?>">Publish new advertisement</a>
	</div><!--end of advise-->

	<?if(count($ads)):?>
	    <?foreach($ads as $ad ):?>
	    <!-- if ad have passed expiration time dont show -->
	    <?if((core::config('advertisement.expire_date') == 0) || 
	    	Date::unix2mysql(Date::mysql2unix($ad->published) + (core::config('advertisement.expire_date') * 24 * 60 * 60)) > Date::unix2mysql(time())):?>
		   	<?if($ad->featured >= Date::unix2mysql(time())):?>
		    	<article class="list well clearfix featured">
		    <?else:?>
		    	<article class="list well clearfix">
		    <?endif?>
		    	<h2>
			    	<?if(is_string($cat)):?>
			    		<?$cat_name = $cat?>
			    	<?else:?>
			    	<?foreach ($cat as $cat){ if($cat->id_category == $ad->id_category) $cat_name = $cat->seoname; }?>
			    	<?endif?>
			    		<a title="<?= $ad->title;?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"> <?=$ad->title; ?></a>
		    	</h2>
		    	
		    	<?if($thumb[$ad->seotitle] != NULL):?>
		    		 <img src="/<?=$thumb[$ad->seotitle]?>" class="img-polaroid advert_img" >
		    	<?endif?>
		    	
		    	<ul>
		    		<?if ($ad->published!=0){?>
			   			<li><b><?= _e('Publish Date');?>:</b> <?= Date::format($ad->published, core::config('general.date_format'))?></li>
			   		<? }?>
			    	<?if ($ad->price!=0){?>
			    		<li class="price"><?= _e('Price');?>: <b><?= $ad->price;?><?=core::config('general.global-currency')?></b></li>
			    	<?}?>  
			    </ul>
			 
			    <p><?= Text::bb2html($ad->description,TRUE);?></p>
			    
			    <a title="<?= $ad->seotitle;?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"><i class="icon-share"></i><?=_e('Read more')?></a>
		    	<?if ($user !== NULL && $user->id_role == 10):?>
		    		<br />
				<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))?>"><?= _e("Edit");?></a> |
				<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>');"><?= _e("Deactivate");?>
				</a> |
				<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Spam?')?>');"><?= _e("Spam");?>
				</a> |
				<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Delete?')?>');"><?= _e("Delete");?>
				</a>

				<?elseif($user !== NULL && $user->id_user == $ad->id_user):?>
					<br/>
				<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))?>"><?=_e("Edit");?></a> |
				<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'deactivate','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>');"><?= _e("Deactivate");?>
				</a>
				<?endif?>
		    </article>
		<?endif?>
		    <?=Alert::show()?>
	    <?endforeach?>

	    <?=$pagination?>
	   <?elseif (count($ads) == 0):?>
	   <!-- Case when we dont have ads for specific category / location -->
	   	<div class="page-header">
			<h3><?= __("We don't have any advertisements for desired values")?></h3>
		</div>
	  <?else:?>
	    	<h2><?=__('Ops! Nothing found')?></h2>
	    	<div class="control"><p><?=__('Improve search :')?></p></div>
	    	<div class="control-group">
				<form class="navbar-search pull-left" method="GET" action="">
					<div class="controls-group">
						<?= FORM::label('Advertisement', __('Advertisement'), array('class'=>'control-label', 'for'=>'Advertisement'))?>
						<div class="control">
							<input type="text" name="advert" class="search-query " placeholder="<?=__('Search')?>">
						</div>
		            </div>
					<div class="controls-group">
						<?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category'))?>
						<div class="control">
							<input type="text" name="cat" class="search-query " placeholder="<?=__('Search')?>">
						</div>
		            </div>
		            <div class="controls-group">
						<?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location'))?>
						<div class="control">
							<input type="text" name="loc" class="search-query " placeholder="<?=__('Search')?>">
						</div>
		            </div>
		            <div class="control-group">
						<?= FORM::button('', 'Publish now', array('type'=>'submit', 'class'=>'btn', 'action'=>Route::url('default')))?>
					</div>
				</form>
			</div>
	  <?endif?>
