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
 * search
 */
Route::set('search',URL::title(__('search')).'.html')
->defaults(array(
        'controller' => 'ad',    
        'action'     => 'advansed_search',
));

/**
 * Captcha / contact
 */
Route::set('contact', URL::title(__('contact')).'.html')
->defaults(array(
		'controller' => 'contact',
		'action'	 => 'index',));

/**
 * maps
 */
Route::set('map', URL::title(__('map')).'.html')
->defaults(array(
        'controller' => 'map',
        'action'     => 'index',));

/**
 * page view public
 */
Route::set('page','p/<seotitle>.html')
->defaults(array(
        'controller' => 'page',    
        'action'     => 'view',
        'seotitle'	 => '',
));


/**
 * rss
 */
Route::set('rss','rss(/<category>(/<location>)).xml')
->defaults(array(
        'controller' => 'feed',    
        'action'     => 'index',
));




//-------END reserved pagesd

/**
 * user admin/panel route
 */
Route::set('oc-panel', 'oc-panel(/<controller>(/<action>(/<id>)))')
->defaults(array(
		'directory'  => 'panel',
		'controller' => 'home',
		'action'     => 'index',
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
Route::set('list', '<category>(/<location>)')
->defaults(array(
		'category'	 => 'all',
		'controller' => 'ad',    
		'action'     => 'listing',
));

/*
	user profile route 
 */
 
Route::set('profile', 'user/<seoname>/<action>')
->defaults(array(
		'controller' => 'user',
		'action'     => 'index',
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
