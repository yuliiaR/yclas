<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Core class for OC, contains commons functions and helpers
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */

class Core {
	
	/**
	 * 
	 * OC version
	 * @var string
	 */
	const version = '2.0 Alpha';
	
	/**
	 * 
	 * Initializes configs for the APP to run
	 */
	public static function initialize()
	{	
		
		// Strip HTML from all request variables
		$_GET    = Core::strip_tags($_GET);
		$_POST   = Core::strip_tags($_POST);
		$_COOKIE = Core::strip_tags($_COOKIE);
		
		/**
		 * Load all the configs from DB
		 */
		//Change the default cache system, based on your config /config/cache.php
		Cache::$default = 'file';//Kohana::$config->load('cache')->default['driver'];//@todo dynamic
		
		//is not loaded yet in Kohana::$config
		Kohana::$config->attach(new ConfigDB(), FALSE);

		//overwrite default Kohana init configs.
		Kohana::$base_url = Core::config('init.base_url');
		
		//enables friendly url @todo from config
		Kohana::$index_file = FALSE;
		//cookie salt for the app
		Cookie::$salt = Core::config('auth.cookie_salt');
		

		// -- i18n Configuration and initialization -----------------------------------------
		//I18n::initialize(Core::config('i18n.locale'),Core::config('i18n.charset'));
		//@todo delete next line:
		//I18n::initialize('es_ES',Core::config('i18n.charset'));
		I18n::initialize();
		//getting the selected theme, and loading defaults
		View::initialize();
		
		//Loading the OC Routes
		if (($init_routes = Kohana::find_file('config','routes')))
		{
			require_once $init_routes[0];//returns array of files but we need only 1 file
		}


	}
	
	/**
	 * Recursively strip html tags an input variable:
	 *
	 * @param   mixed  any variable
	 * @param   string  HTML tags
	 * @return  mixed  sanitized variable
	 */
	public static function strip_tags($value,$allowable_tags=NULL)
	{
		if (is_array($value) OR is_object($value))
		{
			foreach ($value as $key => $val)
			{
				// Recursively strip each value
				$value[$key] = Core::strip_tags($val,$allowable_tags);
			}
		}
		elseif (is_string($value))
		{
			$value = strip_tags($value,$allowable_tags);
		}
	
		return $value;
	}

	/**
     * Shortcut to load a group of configs
     * @param type $group
     * @return array 
     */
    public static function config($group)
    {
    	return Kohana::$config->load($group);
    }
} //end core

/**
 * Common functions
 */


/**
 *
 * Dies and var_dumps
 * @param any $var
 */
function d($var)
{
	die(var_dump($var));
}