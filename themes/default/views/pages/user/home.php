<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row-fluid">
<?=View::factory('sidebar')?>
	<div class="span10">
	<?=Breadcrumbs::render('breadcrumbs')?>
		<div class="hero-unit">
			<h1>
			<?=__('Welcome')?>
			<?=Auth::instance()->get_user()->name?>
				&nbsp; <small><?=Auth::instance()->get_user()->email?> </small>
			</h1>
		</div>

	</div>
	<!--/span-->
</div>
<!--/row-->
