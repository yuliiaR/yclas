<?php defined('SYSPATH') or die('No direct script access.');?>
	 

	<div class="well advise clearfix" id="advise">

		<?if ($category!==NULL):?>
            <p><?=$category->description?></p> 
        <?endif?>

		<i class="icon-pencil"></i> <a title="<?=__('New Advertisement')?>" href="<?=Route::url('post_new')?>"><?=__('Publish new advertisement')?></a>

	</div><!--end of advise-->

	<?if(count($ads)):?>
	    <?foreach($ads as $ad ):?>
	   
	       	<?if($ad->featured >= Date::unix2mysql(time())):?>
		    	<article class="list well clearfix featured">
		    <?else:?>
		    	<article class="list well clearfix">
		    <?endif?>
		    	<h2>
		    	    <? $cat_name = $ad->category->seoname; ?>
		    		<a title="<?= $ad->title;?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"> <?=$ad->title; ?></a>
		    	</h2>
		    	
		    	<?if($ad->get_first_image() !== NULL):?>
		    		 <img src="/<?=$ad->get_first_image()?>" class="img-polaroid advert_img" >
		    	<?endif?>
		    	
		    	<ul>
		    		<?if ($ad->published!=0){?>
			   			<li><b><?=__('Publish Date');?>:</b> <?= Date::format($ad->published, core::config('general.date_format'))?></li>
			   		<? }?>
			    	<?if ($ad->price!=0){?>
			    		<li class="price"><?=__('Price');?>: <b><?= $ad->price;?><?=core::config('general.global_currency')?></b></li>
			    	<?}?>  
			    </ul>
			 
			    <p><?=substr(Text::removebbcode($ad->description),0, 255);?></p>
			    
			    <a title="<?= $ad->seotitle;?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"><i class="icon-share"></i><?=__('Read more')?></a>
		    	<?if ($user !== NULL && $user->id_role == 10):?>
		    		<br />
				<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))?>"><?=__("Edit");?></a> |
				<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>');"><?=__("Deactivate");?>
				</a> |
				<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Spam?')?>');"><?=__("Spam");?>
				</a> |
				<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Delete?')?>');"><?=__("Delete");?>
				</a>

				<?elseif($user !== NULL && $user->id_user == $ad->id_user):?>
					<br/>
				<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))?>"><?=__("Edit");?></a> |
				<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'deactivate','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>');"><?=__("Deactivate");?>
				</a>
				<?endif?>
		    </article>
		
		    <?=Alert::show()?>
	    <?endforeach?>

	    <?=$pagination?>
	   <?elseif (count($ads) == 0):?>
	   <!-- Case when we dont have ads for specific category / location -->
	   	<div class="page-header">
			<h3><?= __("We don't have any advertisements in this category")?></h3>
		</div>
	  
	  <?endif?>
