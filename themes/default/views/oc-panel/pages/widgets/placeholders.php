<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span4">

	<?foreach ($placeholders as $placeholder=>$widgets):?>
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header"><?=$placeholder?></li>
		</ul>

		<?foreach ($widgets as $widget):?>
			<?=$widget?>
		<?endforeach?>
	</div>
	<?endforeach?>


</div>
<!--/span-->