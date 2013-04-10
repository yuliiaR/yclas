<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		<div class="page-header">
			<article class="list well clearfix">
				<h1><?= __('User Profile')?></h1>
				<p>Name : <?=$user->name?></p>
				<p>Email : <?= $user->email?></p>
				<p>Created : <?= Date::format($user->created, core::config('general.date_format')) ?></p>
				<p>Number Logins : <?= $user->logins?></p>
				<p>Last Login : <?= $user->last_login?></p>
				<p>Seoname : <?= $user->seoname?></p>
			</article>
		</div>
		<?if($profile_ads!==NULL):?>
			<?foreach($profile_ads as $ads):?>
			<article class="list well clearfix">
				<h4><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads['category'],'seotitle'=>$ads['seotitle']))?>"><?=$ads['title']?></a></h4>
				<p><?=$ads['description']?><p>
					<?if ($user !== NULL && $user->id_role == 10):?>
	    		<br />
			<a href="<?=Route::url('default', array('controller'=>'ad','action'=>'update','id'=>$ads['id_ad']))?>"><?= _e("Edit");?></a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ads['id_ad']))?>" 
				onclick="return confirm('<?=__('Deactivate?')?>');"><?= _e("Deactivate");?>
			</a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ads['id_ad']))?>" 
				onclick="return confirm('<?=__('Spam?')?>');"><?= _e("Spam");?>
			</a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ads['id_ad']))?>" 
				onclick="return confirm('<?=__('Delete?')?>');"><?= _e("Delete");?>
			</a>

			<?elseif($user !== NULL && $user->id_user == $ads['id_user']):?>
				<br/>
			<a href="<?=Route::url('default', array('controller'=>'ad','action'=>'update','id'=>$ads['id_ad']))?>"><?=_e("Edit");?></a> 
			<?endif?>
			</article>
			<?endforeach?>
		<?endif?>
	</div>
	<!--/span-->
</div>
<!--/row-->
