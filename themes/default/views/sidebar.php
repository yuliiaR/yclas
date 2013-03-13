<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span2">
	<div class="well sidebar-nav">
	  <ul class="nav nav-list">
	    <li class="nav-header">Sidebar</li>
	    <li class="active"><a href="#">Electronic & Light</a></li>
	    <li><a href="#">Friends and entertainment</a></li>
	    <li><a href="#">Link</a></li>
	    <li><a href="#">Link</a></li>
	    <li class="nav-header">Sidebar</li>
	    <li><a href="#">Link</a></li>
	    <li><a href="#">Link</a></li>
	    <li><a href="#">Link</a></li>
	    <li class="nav-header">Sidebar</li>
	    <li><a href="#">Link</a></li>
	    <li><a href="#">Link</a></li>
	    <li><a href="#">Link</a></li>
	  </ul>
	  <!--LOAD ACTIVE WIDGETS-->
<?$view = Widget::get('sidebar') ?>
<?if($view !== NULL):?>
	<?foreach ( $view as $view => $val):?>
		<?=$val->class; // load widget class?> 
		<?=$val; // load widget view?>
	<?endforeach?>
<?endif?>
</footer>
	</div><!--/.well -->
</div><!--/span-->
