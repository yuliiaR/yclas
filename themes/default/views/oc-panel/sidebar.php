<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">


			<?if ($user->has_access_to_any('ad,category,location,user,role,access,widget,content')):?>
				<li class="nav-header"><?=__('Administration')?></li>
				<?sidebar_link(__('Ads'),'ad')?>
				<?sidebar_link(__('Moderation'),'ad','moderate')?>
				<?sidebar_link(__('Categories'),'category')?>
				<?sidebar_link(__('Locations'),'location')?>
				<?sidebar_link(__('Users'),'user')?>
				<?sidebar_link(__('User Roles'),'role')?>
				<?sidebar_link(__('Roles access'),'access')?>
				<?sidebar_link(__('Orders'), 'order')?>
				<?sidebar_link(__('Widgets'), 'widget')?>
				<?sidebar_link(__('Content'), 'content')?>
			<? endif ?>

			<?if ($user->has_access_to_any('settings,config')):?>
				<li class="nav-header"><?=__('Settings')?></li>
				<?sidebar_link(__('General'), 'settings','general')?>
				<?sidebar_link(__('Payment'), 'settings','payment')?>
				<?sidebar_link(__('Email'), 'settings','email')?>
				<?sidebar_link(__('Form'), 'settings','form')?>
				<?sidebar_link(__('All configs'), 'config')?>
			<?endif?>

			<?if ($user->has_access_to_any('tools')):?>
				<li class="nav-header"><?=__('Tools')?></li>
				<?sidebar_link(__('Sitemap'), 'tools','sitemap')?>
				<?sidebar_link(__('Migration'), 'tools','migration')?>
				<?sidebar_link(__('Optimize'), 'tools','optimize')?>
				<?sidebar_link(__('Cache'), 'tools','cache')?>
				<?sidebar_link(__('PHP Info'), 'tools','phpinfo')?>
			<?endif?>

			<?if ($user->has_access_to_any('profile')):?>
				<li class="nav-header"><?=__('Profile')?></li>
				<?sidebar_link(__('Edit'), 'profile','edit')?>
				<?sidebar_link(__('Change password'), 'profile','changepass')?>
				<li><a
					href="<?=Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname))?>">
					<?=__('Public profile')?>
				</a>
				</li>
			<?endif?>

			<li class="divider"></li>
			<li class="nav-header">Open Classifieds</li>
			<li><a href="http://open-classifieds.com/themes/"><?=__('Themes')?></a></li>
			<li><a href="http://open-classifieds.com/support/"><?=__('Support')?></a></li>
			<li class="divider"></li>
			
		</ul>
		<a href="https://twitter.com/openclassifieds"
				onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
				class="twitter-follow-button" data-show-count="false"
				data-size="large">Follow @openclassifieds</a><br />
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
	<!--/.well -->
</div>
<!--/span-->
