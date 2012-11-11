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
 */
Route::set('post_new', URL::title(__('publish new')).'.html')
->defaults(array(
		'controller' => 'post',    
		'action'     => 'new',
));

//-------END reserved pages

/**
 * SERP / listing
 */
Route::set('listing', '<category>(/<location>)')
->defaults(array(
		'controller' => 'post',    
		'action'     => 'listing',
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
 * Item / post view public
 */
Route::set('post', '<seotitle>.html')
->defaults(array(
		'controller' => 'post',    
		'action'     => 'view',
));

/**
 * user and admin route, we use this instead of the default route
 */
Route::set('user', 'oc-<directory>/<controller>/<action>(/<id>)',array('directory' => '(admin|user)'))
->defaults(array(
		'directory'  => 'user',
        'controller' => 'profile',
        'action'     => 'index',
));

/**
 * Error router
 */
Route::set('error', 'error/<action>/<origuri>/<message>',
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