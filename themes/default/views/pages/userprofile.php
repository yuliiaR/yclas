<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h3><?= __('User Profile')?></h3>
</div>
<div class="page-header">
	<article class="list well clearfix">
		<h3><?=$user->name?></h3>
		<p><b><?=__('Email')?>: </b><?= $user->email?></p>
		<p><b><?=__('Created')?>: </b><?= Date::format($user->created, core::config('general.date_format')) ?></p>
		<p><b><?=__('Last Login')?>: </b><?= Date::format($user->last_login, core::config('general.date_format'))?></p>
	</article>
</div>
<div class="page-header">
	<h3><?=$user->name.' '.__(' advertisements')?></h3>

	<?if($profile_ads!==NULL):?>
		<?foreach($profile_ads as $ads):?>
		<?if($ads->featured >= Date::unix2mysql(time())):?>
	    	<article class="list well clearfix featured">
	    <?else:?>
		<article class="list well clearfix">
		<?endif?>
			<h4><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads->category,'seotitle'=>$ads->seotitle))?>"><?=$ads->title?></a></h4>
			<p><strong>Description: </strong><?=Text::removebbcode($ads->description)?><p>
		<?if($ads->published != NULL):?>
			<p><b><?=__('Publish Date');?>:</b> <?= Date::format($ads->published, core::config('general.date_format'))?><p>
		<?else:?>
			<p><b><?=__('Publish Date');?>:</b> <?=__('Not yet published')?><p>
		<?endif?>

		<?$visitor = Auth::instance()->get_user()?>
		
		<?if ($visitor != FALSE && $visitor->id_role == 10):?>
			<br />
			<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ads->id_ad))?>"><?=__("Edit");?></a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ads->id_ad))?>" 
				onclick="return confirm('<?=__('Deactivate?')?>');"><?=__("Deactivate");?>
			</a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ads->id_ad))?>" 
				onclick="return confirm('<?=__('Spam?')?>');"><?=__("Spam");?>
			</a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ads->id_ad))?>" 
				onclick="return confirm('<?=__('Delete?')?>');"><?=__("Delete");?>
			</a>

			<?elseif($visitor != FALSE && $visitor->id_user == $ads->id_user):?>
			<br/>
			<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ads->id_ad))?>"><?=__("Edit");?></a> 
		<?endif?>
		</article>
		<?endforeach?>
	<?endif?>
</div>
	
