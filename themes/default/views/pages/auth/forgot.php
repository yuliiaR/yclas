<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
	<?=View::factory('sidebar')?>
	<div class="span10">
	
	<div class="page-header">
		<h1><?=__('Remember password')?></h1>
	</div>

	<?if ($msg):?>
  		<h2><?=$msg?></h2>
    <?else:?>
		<?=View::factory('pages/auth/forgot-form')?>
	<?endif?>
	
	</div><!--/span--> 
</div><!--/row-->