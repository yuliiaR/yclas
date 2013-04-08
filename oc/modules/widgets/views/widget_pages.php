<?php defined('SYSPATH') or die('No direct script access.');?>

<div id="<?=$widget->widget_name?>" >
	<h3><?=$widget->page_title?></h3>
	<ul>
	<?print_r($widget->page_items)?>
	</ul>
</div>
