<?php defined('SYSPATH') or die('No direct access allowed.');

// -- Routes Configuration and initialization -----------------------------------------

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

/**
 * Reserved pages for OC usage. They use the i18n translation
 * We will use them with extension .htm to avoid repetitions with others.
 */

/**
 * Item / post new
 * URL::title(__('publish new'))
 */
Route::set('post_new', URL::title(__('publish new')).'.html')
->defaults(array(
		'controller' => 'new',    
		'action'     => 'index',
));

/**
 * Captcha / contact
 */
Route::set('contact', URL::title(__('contact')).'.html')
->defaults(array(
		'controller' => 'contact',
		'action'	 => 'index',));

//-------END reserved pagesd

/**
 * user admin/panel route
 */
Route::set('oc-panel', 'oc-panel/(<controller>(/<action>(/<id>)))')
->defaults(array(
		'directory'  => 'panel',
		'controller' => 'home',
		'action'     => 'index',
));

/**
 * user/admin edit ad route
 */
Route::set('update', '/(<action>/(<seotitle>(/<id>(/<img_name>))))')
->defaults(array(
		'controller' => 'ad',
		'action'     => 'update',
));

/**
 * Item / ad view (public)
 */
Route::set('ad', '<category>/<seotitle>.html')
->defaults(array(
		'controller' => 'ad',    
		'action'     => 'view',
));

/**
 * Sort by Category / Location
 */
Route::set('sort_by', 'sort_by/<category>(/<location>)')
->defaults(array(
		'controller' => 'ad',    
		'action'     => 'sort_category',
));

/*
	user profile route 
 */
 
Route::set('profile', 'user/<seoname>(/<action>)')
->defaults(array(
		'controller' => 'user',
		'action'     => 'index',
));


/**
 * page view public
 */
Route::set('page','p/<seotitle>.html')
->defaults(array(
		'controller' => 'page',    
		'action'     => 'view',
));


/**
 * Error router
 */
Route::set('error', 'oc-error/<action>/<origuri>/<message>',
array('action' => '[0-9]++',
                    	  'origuri' => '.+', 
                    	  'message' => '.+'))
->defaults(array(
    'controller' => 'error',
    'action'     => 'index'
));

/**
 * Default route
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
->defaults(array(
		'controller' => 'home',
		'action'     => 'index',
));
