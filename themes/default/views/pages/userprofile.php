<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		<div class="page-header">
			<article class="list well clearfix">
				<h1><?= __('User Profile')?></h1>
				<p>Name : <?=$user->name?></p>
				<p>Email : <?= $user->email?></p>
				<p>Created : <?= $user->created?></p>
				<p>Number Logins : <?= $user->logins?></p>
				<p>Last Login : <?= $user->last_login?></p>
				<p>Seoname : <?= $user->seoname?></p>
			</article>
		</div>
	</div>
	<!--/span-->
</div>
<!--/row-->
