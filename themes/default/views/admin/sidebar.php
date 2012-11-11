<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header"><?=__('Administration')?></li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'post'))?>">
				<?=__('Ads')?>
			</a>
			</li>
			<li class="active"><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'category'))?>">
				<?=__('Categories')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'location'))?>">
				<?=__('Locations')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'account'))?>">
				<?=__('Accounts')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'page'))?>">
				<?=__('Pages')?>
			</a>
			</li>
			<li class="nav-header"><?=__('Settings')?></li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'config'))?>">
				<?=__('General')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'settings','action'=>'visual'))?>">
				<?=__('Visual')?>
			</a>
			</li>
			<li class="nav-header"><?=__('Tools')?></li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'tools','action'=>'optimize'))?>">
				<?=__('Optimize')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'tools','action'=>'backup'))?>">
				<?=__('Backup')?>
			</a>
			</li>
			<li><a
				href="<?=Route::url('user',array('directory'=>'admin','controller'=>'tools','action'=>'phpinfo'))?>">
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
