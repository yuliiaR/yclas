<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">

			<?if ($user->has_access_to_any('post,category')):?>

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
			<li class="nav-header"><?=__('Settings')?></li>
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'config'))?>">
				<?=__('Config')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'visual'))?>">
				<?=__('Visual')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'generalconf'))?>">
				<?=__('General')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'paymentconf'))?>">
				<?=__('Payment')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'emailconf'))?>">
				<?=__('Email')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'formconf'))?>">
				<?=__('Form Configuration')?>
			</a>
			</li>
			<li class="nav-header"><?=__('Tools')?></li>

			<?sidebar_link(__('Sitemap'), 'tools','sitemap')?>
			<?sidebar_link(__('Migration'), 'tools','migration')?>
			<?sidebar_link(__('Optimize'), 'tools','optimize')?>
			<?sidebar_link(__('Cache'), 'tools','cache')?>
			<?sidebar_link(__('PHP Info'), 'tools','phpinfo')?>

			<li class="divider"></li>
			<li class="nav-header">Open Classifieds</li>
			<li><a href="http://open-classifieds.com/themes/"><?=__('Themes')?>
			</a></li>
			<li><a href="http://open-classifieds.com/support/"><?=__('Support')?>
			</a></li>
			<li><a href="http://j.mp/ocdonate" target="_blank">
					<img src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" alt="">
			</a></li>
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
