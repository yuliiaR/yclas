<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		<div class="page-header">
			<h1><?=$user->name?></h1>
		</div>
		<?php var_dump($user)?>
	</div>
	<!--/span-->
</div>
<!--/row-->
