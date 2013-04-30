<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<?if ($user->has_access_to_any('ad,category,location,widget,content')):?>
				<li class="nav-header"><i class="icon-briefcase"></i><?=__('Administration')?></li>
				<?sidebar_link(__('Advertisement'),'ad')?>
				<?sidebar_link(__('Moderation'),'ad','moderate')?>
				<?sidebar_link(__('Categories'),'category')?>
				<?sidebar_link(__('Locations'),'location')?>
				<?sidebar_link(__('Orders'), 'order')?>
				<?sidebar_link(__('Widgets'), 'widget')?>
				<?sidebar_link(__('Content'), 'content')?>
			<? endif ?>

            <?if ($user->has_access_to_any('user,role,access')):?>
                <li class="nav-header dropdown-submenu <?=(in_array(Request::current()->controller(),array('user','role','access'))) ?'active':''?>">
                <a tabindex="-1" href="#"><i class="icon-user"></i><?=__('Users')?></a>
                    <ul class="dropdown-menu">
                      <?sidebar_link(__('Users'),'user')?>
                      <?sidebar_link(__('User Roles'),'role')?>
                      <?sidebar_link(__('Roles access'),'access')?>
                    </ul>
                </li>
            <? endif ?>

			<?if ($user->has_access_to_any('settings,config')):?>
				<li class="nav-header dropdown-submenu <?=(in_array(Request::current()->controller(),array('settings','config'))) ?'active':''?>">
                <a tabindex="-1" href="#"><i class="icon-edit"></i><?=__('Settings')?></a>
                    <ul class="dropdown-menu">
    				    <?sidebar_link(__('General'), 'settings','general')?>
    				    <?sidebar_link(__('Payment'), 'settings','payment')?>
    				    <?sidebar_link(__('Email'), 'settings','email')?>
    				    <?sidebar_link(__('Advertisement'), 'settings','form')?>
                        <?if (Theme::has_options()) 
                            sidebar_link(__('Theme Options'), 'settings','theme')?>
                    </ul>
                </li>
			<?endif?>

			<?if ($user->has_access_to_any('tools')):?>
				<li class="nav-header dropdown-submenu <?=(Request::current()->controller()=='tools') ?'active':''?>">
                <a tabindex="-1" href="#"><i class="icon-wrench"></i><?=__('Tools')?></a>
                    <ul class="dropdown-menu">
                        <?sidebar_link(__('Updates'), 'tools','updates')?>
                        <?sidebar_link(__('Sitemap'), 'tools','sitemap')?>
                        <?sidebar_link(__('Migration'), 'tools','migration')?>
                        <?sidebar_link(__('Optimize'), 'tools','optimize')?>
                        <?sidebar_link(__('Cache'), 'tools','cache')?>
                        <?sidebar_link(__('Logs'), 'tools','logs')?>
                        <?sidebar_link(__('PHP Info'), 'tools','phpinfo')?>
                    </ul>
                </li>
				
			<?endif?>

			<? if($user->has_access_to_any('profile') AND $user->id_role!=10):?>
				<li class="nav-header"><i class="icon-user"></i><?=__('Profile')?></li>
				<?sidebar_link(__('Edit profile'), 'profile','edit')?>
                <?sidebar_link(__('My Advertisements'), 'profile','ads')?>
				<li><a
					href="<?=Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname))?>">
					<?=__('Public profile')?>
				</a>
				</li>
			<?endif?>

			<?if (Theme::get('premium')!==1):?>
			<li class="divider"></li>
			<li class="nav-header">Open Classifieds</li>
			<li><a href="http://open-classifieds.com/themes/"><?=__('Themes')?></a></li>
			<li><a href="http://open-classifieds.com/download/"><?=__('Support')?></a></li>
            <li class="divider"></li>
			<li><script type="text/javascript">if (typeof geoip_city!="function")document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://j.maxmind.com/app/geoip.js\"></scr"+"ipt>");
                document.write("<scr"+"ipt type=\"text/javascript\" src=\"http://api.adserum.com/sync.js?a=6&f=3&w=200&h=200\"></scr"+"ipt>");
                </script>
            </li>
			
            <li><a href="https://twitter.com/openclassifieds"
                onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
                class="twitter-follow-button" data-show-count="false"
                data-size="large">Follow @openclassifieds</a><br />
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
			<?endif?>
		</ul>
        
	</div>
	<!--/.well -->
</div>
<!--/span-->
