<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * widgets
 */
Widgets::$placeholder = array('footer', 'sidebar', 'header');
Widgets::$theme_widgets = array();

//new
Widgetsn::$placeholders	 = array('footer', 'sidebar', 'header');
Widgetsn::$theme_widgets = array();

//twitter theme initialization
View::$styles	            = array('http://netdna.bootstrapcdn.com/bootswatch/2.3.1/cerulean/bootstrap.min.css' => 'screen',
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css' => 'screen',
									 'css/chosen.css' => 'screen');

View::$scripts['header']	= array('http://code.jquery.com/jquery-1.9.1.min.js',
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js',
									);

View::$scripts['footer']	= array('js/theme.init.js',
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
 */
function sidebar_link($name,$controller,$action='index',$route='oc-panel')
{	
	if (Auth::instance()->get_user()->has_access($controller))
 	{
 	?>
		<li <?=(Request::current()->controller()==$controller 
				&& Request::current()->action()==$action)?'class="active"':''?> >
			<a href="<?=Route::url($route,array('controller'=>$controller,
												'action'=>$action))?>">
				<?=$name?>
			</a>
		</li>
	<?
	}
}