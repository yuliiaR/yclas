<?php defined('SYSPATH') or die('No direct script access.');?>
<hr>
<footer>
<p>
&copy; <a href="http://open-classifieds.com" title="Open Source PHP Classifieds">Open Classifieds</a> 
2009 - <?=date('Y')?>
</p>
<!--LOAD ACTIVE WIDGETS-->
<?$view = Widgets::get('footer') ?>
<?if($view !== NULL):?>
<?foreach ( $view as $view => $val):?>
	<?=$val; // load widget view?>
<?endforeach?>
<?endif?>
</footer>