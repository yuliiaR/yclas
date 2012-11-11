<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
		<div class="page-header">
			<h1><?=$page->title?></h1>
		</div>
		<?=Text::bb2html($page->description,TRUE)?>
	</div>
	<!--/span-->
</div>
<!--/row-->
