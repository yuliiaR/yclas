<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">

			<?if ($user->has_access_to_any('post,category')):?>

				<li class="nav-header"><?=__('Administration')?></li>

				<?php sidebar_link(__('Ads'),'ad')?>
				<?php sidebar_link(__('Moderation'),'ad','moderate')?>
				<?php sidebar_link(__('Categories'),'category')?>
				<?php sidebar_link(__('Locations'),'location')?>
				<?php sidebar_link(__('Users'),'user')?>
				<?php sidebar_link(__('User Roles'),'role')?>
				<?php sidebar_link(__('Roles access'),'access')?>
				<?php sidebar_link(__('Orders'), 'order')?>
				<?php sidebar_link(__('Widgets'), 'widget')?>
				<?php sidebar_link(__('Content'), 'content')?>
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
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'optimize'))?>">
				<?=__('Optimize')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'backup'))?>">
				<?=__('Backup')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'phpinfo'))?>">
					PHP Info</a>
			</li>
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
