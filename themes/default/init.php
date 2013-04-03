<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * widgets
 */
Widgets::$placeholder = array('footer', 'sidebar', 'header');
Widgets::$theme_widgets = array();

//twitter theme initialization
View::$styles	            = array('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css' => 'screen',
									 'css/chosen.css' => 'screen');

//@todo add in the header some sompatibility js?  html5shiv already at main template
View::$scripts['header']	= array('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js',
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js',
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

