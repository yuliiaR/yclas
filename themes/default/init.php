<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Ocean Free
  * Description: Clean free theme that includes full admin. It has publicity. Do not delete this theme, all the views depend in this theme.
  * Tags: HTML5, Admin, Free, Bootstrap
  * Version: 1.1.
  * Author: Chema <chema@garridodiaz.com>
  * License: GPL v3
  */


/**
 * placeholders & widgets for this theme
 */
Widgets::$theme_placeholders	= array('footer', 'sidebar');
Widgets::$theme_widgets 		= array();

//twitter theme initialization
Theme::$styles	            = array('css/styles.css' => 'screen',									
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css' => 'screen',
                                    'http://netdna.bootstrapcdn.com/bootswatch/2.3.1/cerulean/bootstrap.min.css' => 'screen',
									);

Theme::$scripts['header']	= array('http://code.jquery.com/jquery-1.9.1.min.js',
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js',
									);

// Theme::$scripts['footer']	= array('js/theme.init.js',);


/**
 * custom options for the theme
 * @var array
 */
Theme::$options = array(    'rss_limit' => array(   'type'      => 'numeric',
                                                        'display'   => 'select',
                                                        'label'     => __('Number items to display'),
                                                        'options'   => array_combine(range(1,50),range(1,50)), 
                                                        'default'   => 5,
                                                        'required'  => TRUE),

                                'rss_expire' => array(  'type'      => 'numeric',
                                                        'display'   => 'text',
                                                        'label'     => __('How often we refresh the RSS, in seconds'),
                                                        'default'   => 86400,
                                                        'required'  => TRUE),

                                'rss_url'  => array(    'type'      => 'uri',
                                                        'display'   => 'text',
                                                        'label'     => __('RSS url address'),
                                                        'default'   => 'http://feeds.feedburner.com/OpenClassifieds',
                                                        'required'  => TRUE),

                                'rss_title'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('RSS title displayed'),
                                                        'default'   => 'Open Classifieds',
                                                        'required'  => FALSE),
                                );


/**
 * custom error alerts
 */
Form::$errors_tpl 	= '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>
			       		<h4 class="alert-heading">%s</h4>
			        	<ul>%s</ul></div>';
Form::$error_tpl 	= '<div class="alert"><a class="close" data-dismiss="alert">×</a>%s</div>';


Alert::$tpl 	= 	'<div class="alert alert-%s">
					<a class="close" data-dismiss="alert" href="#">×</a>
					<h4 class="alert-heading">%s</h4>%s
					</div>';


/**
 * Theme Functions
 * 
 */

/**
 * @todo this belongs to the admin, so needs to be loaded no matter, the theme. not a good place here...
 * generates a link used in the admin sidebar
 * @param  string $name       translated name in the A
 * @param  string $controller
 * @param  string $action     
 * @param  string $route      
 * @param  string $icon         class name of bootstrap icon to append with nav-link 
 */
function sidebar_link($name,$controller,$action='index',$route='oc-panel', $icon=NULL)
{	
	if (Auth::instance()->get_user()->has_access($controller))
 	{
 	?>
		<li <?=(Request::current()->controller()==$controller 
				&& Request::current()->action()==$action)?'class="active"':''?> >
			<a href="<?=Route::url($route,array('controller'=>$controller,
												'action'=>$action))?>">
				<?if($icon!==NULL)?>
                    <i class="<?=$icon?>"></i>
                <?=$name?>
			</a>
		</li>
	<?
	}
}

/**
 * nav_link generates a link for main nav-bar
 * @param  string $name       translated name in the A
 * @param  string $controller
 * @param  string $action  
 * @param  string $icon 		class name of bootstrap	icon to append with nav-link   
 * @param  string $route      
 * @param  string $style extra class div 
 */
function nav_link($name, $controller, $icon=NULL, $action='index', $route='default' , $style = NULL)
{	
	
 	?>
		<li class="<?=(Request::current()->controller()==$controller 
				&& Request::current()->action()==$action)?'active':''?> <?=$style?>" >
			<a href="<?=Route::url($route,array('controller'=>$controller,
												'action'=>$action))?>">
				<?if($icon!==NULL)?>
                    <i class="<?=$icon?>"></i>
                <?=$name?>
			</a>
		</li>
	<?
}