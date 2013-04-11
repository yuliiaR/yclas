<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
        <div class="container"><a class="brand"
        	href="<?=Route::url('oc-panel',array('controller'=>'home'))?>"><?=__('Administration')?></a>
            <div class="nav-collapse">
                <ul class="nav">
                    <?=sidebar_link('','home','index','oc-panel','icon-home icon-white')?>
                	<?=sidebar_link(__('Stats'),'stats')?>
                    <?=sidebar_link(__('Widgets'),'widget')?>
            	    <li  class="dropdown "><a href="#" class="dropdown-toggle"
            		      data-toggle="dropdown"><i class="icon-plus icon-white"></i> <?=__('New')?> <b class="caret"></b></a>
                    	<ul class="dropdown-menu">
                            <?=sidebar_link(__('Category'),'category')?>
                            <?=sidebar_link(__('Location'),'location')?>
                            <?=sidebar_link(__('User'),'user')?>
                            <?=sidebar_link(__('Role'),'role')?>
                            <?=sidebar_link(__('Content'),'content')?>
                    		<li class="divider"></li>
                    		<li><a href="<?=Route::url('post_new')?>">
                    			<i class="icon-pencil"></i><?=__('Publish new ')?></a>	</li>
                    	</ul>
            	   </li>
                    <?=nav_link(__('View site'),'home')?>
                </ul>
                <div class="btn-group pull-right">
                    <?=View::factory('widget_login')?>
                </div>
            </div> <!--/.nav-collapse -->
        </div>
    </div>
</div>