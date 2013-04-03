<?php defined('SYSPATH') or die('No direct script access.');?>
<?if (Auth::instance()->logged_in()):?>
<?if (Auth::instance()->get_user()->id_role == 10): //@todo ad acccess control?>
<a class="btn btn-success"
	href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
	<i class="icon-user icon-white"></i> <?=Auth::instance()->get_user()->email?>
</a>
<a class="btn dropdown-toggle btn-success" data-toggle="dropdown"
	href="#"> <span class="caret"></span>
</a>
<ul class="dropdown-menu">
	
	<li><a	href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>"><i
			class="icon-cog"></i> <?=__('Admin')?></a></li>
	
	<li><a	href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>"><i
			class="icon-edit"></i> <?=__('Edit profile')?></a></li>
	<li><a	href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>"><i
			class="icon-lock"></i> <?=__('Change Password')?></a></li>
	<li class="divider"></li>
	<li><a
		href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
			<i class="icon-off"></i> <?=__('Logout')?>
	</a>
	</li>
</ul>
<?else: // if normal user?>
	<a class="btn btn-success"
		href="<?=Route::url('profile',array('controller'=>'user','action'=>'index', 'seoname'=> Auth::instance()->get_user()->seoname))?>">
		<i class="icon-user icon-white"></i> <?=Auth::instance()->get_user()->email?>
	</a>
	<a class="btn dropdown-toggle btn-success" data-toggle="dropdown"
		href="#"> <span class="caret"></span>
	</a>
	<ul class="dropdown-menu">
		
		<li><a	href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>"><i
				class="icon-edit"></i> <?=__('Edit profile')?></a></li>
		<li><a	href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>"><i
				class="icon-lock"></i> <?=__('Change Password')?></a></li>
		<li class="divider"></li>
		<li><a
			href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
				<i class="icon-off"></i> <?=__('Logout')?>
		</a>
		</li>
	</ul>
<?endif?>
<?else:?>
<a class="btn" data-toggle="modal" title="<?=__('Login')?>"
	href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
	<i class="icon-user"></i> <?=__('Login')?>
</a>
<?endif?>