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
	    <?if (Auth::instance()->logged_in()):?>
	    <?if (Auth::instance()->get_user()->id_role == 1): //@todo ad acccess control?>
		    <li class="nav-header">User Profile</li>
		    <li><a href="<?=Route::url('profile',array('controller'=>'user','action'=>'index', 'seoname'=> Auth::instance()->get_user()->seoname))?>">Home</a></li>
		   
		    <li><a href="#">Link</a></li>
		<?endif?>
		<?endif?>
	  </ul>
	  <!--LOAD ACTIVE WIDGETS-->
<?$view = Widgets::get('sidebar') ?>
<?if($view !== NULL):?>
	<?foreach ( $view as $view => $val):?>
		<?=$val; // load widget view?>
	<?endforeach?>
<?endif?>
</footer>
	</div><!--/.well -->
</div><!--/span-->
