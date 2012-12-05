<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="navbar navbar-fixed-top navbar-inverse">
<div class="navbar-inner">
<div class="container"><a class="brand"
	href="<?=Route::url('oc-panel',array('controller'=>'home'))?>"><?=__('Administration')?></a>
<div class="nav-collapse">
<ul class="nav">
	<li><a href="<?=Route::url('default')?>"><i class="icon-home icon-white"></i></a></li>
	<li><a
		href="<?=Route::url('oc-panel',array('controller'=>'stats'))?>">
		<?=__('Stats')?></a></li>
	<li><a
		href="<?=Route::url('oc-panel',array('controller'=>'stats'))?>">
		<?=__('Clean Cache')?></a></li>
	<li  class="dropdown active"><a href="#" class="dropdown-toggle"
		data-toggle="dropdown"><i class="icon-plus icon-white"></i> <?=__('New')?> <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'create'))?>">
			<?=__('Category')?> </a></li>
		<li><a href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'create'))?>">
			<?=__('Location')?> </a></li>
		<li><a href="<?=Route::url('oc-panel',array('controller'=>'page','action'=>'create'))?>">
			<?=__('Page')?> </a></li>
		<li class="divider"></li>
		<li><a href="<?=Route::url('post_new')?>">
						<i class="icon-pencil"></i>
						<?=__('Publish new ')?>
					</a>	</li>
	</ul>
	</li>
</ul>

<div class="btn-group pull-right"><?=View::factory('widget_login')?></div>
</div>
<!--/.nav-collapse --></div>
</div>
</div>