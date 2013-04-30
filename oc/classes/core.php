<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Core class for OC, contains commons functions and helpers
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Core {
	
	/**
	 * 
	 * OC version
	 * @var string
	 */
	const version = '2.0.RC1';

    /**
     * original requested data
     * @var array
     */
    public static $_POST_ORIG;
    public static $_GET_ORIG;
    public static $_COOKIE_ORIG;

	
	/**
	 * 
	 * Initializes configs for the APP to run
	 */
	public static function initialize()
	{	
        //before cleaning getting a copy of the original in case we need it.
        self::$_POST_ORIG   = $_POST;
        self::$_GET_ORIG    = $_GET;
        self::$_COOKIE_ORIG = $_COOKIE;
      
		// Strip HTML from all request variables
		$_GET    = Core::strip_tags($_GET);
		$_POST   = Core::strip_tags($_POST);
		$_COOKIE = Core::strip_tags($_COOKIE);
		
		/**
		 * Load all the configs from DB
		 */
		//Change the default cache system, based on your config /config/cache.php
		Cache::$default = Core::config('cache.default');
		
		//is not loaded yet in Kohana::$config
		Kohana::$config->attach(new ConfigDB(), FALSE);

		//overwrite default Kohana init configs.
		Kohana::$base_url = Core::config('general.base_url');
		
		//enables friendly url @todo from config
		Kohana::$index_file = FALSE;
		//cookie salt for the app
		Cookie::$salt = Core::config('auth.cookie_salt');
		

		// -- i18n Configuration and initialization -----------------------------------------
		I18n::initialize(Core::config('i18n.locale'),Core::config('i18n.charset'));
		
		
		
		//Loading the OC Routes
		if (($init_routes = Kohana::find_file('config','routes')))
		{
			require_once $init_routes[0];//returns array of files but we need only 1 file
		}

        //getting the selected theme, and loading defaults
        Theme::initialize();

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

    /**
     * shortcut for the query method $_GET
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function get($key,$default=NULL)
    {
    	return (isset($_GET[$key]))?$_GET[$key]:$default;
    }

    /**
     * shortcut for $_POST[]
     * @param  [type] $key     [description]
     * @param  [type] $default [description]
     * @return [type]          [description]
     */
    public static function post($key,$default=NULL)
    {
    	return (Request::current()->post($key)!==NULL)?Request::current()->post($key):$default;
    }

    /**
     * write to file
     * @param $filename fullpath file name
     * @param $content
     * @return boolean
     */
    public static function fwrite($filename,$content)
    {
        $file = fopen($filename, 'w');
        if ($file)
        {//able to create the file
            fwrite($file, $content);
            fclose($file);
            return TRUE;
        }
        return FALSE;   
    }
    
    /**
     * read file content
     * @param $filename fullpath file name
     * @return $string or false if not found
     */
    public static function fread($filename)
    {
        if (is_readable($filename))
        {
            $file = fopen($filename, 'r');
            if ($file)
            {//able to read the file
                $data = fread($file, filesize($filename));
                fclose($file);
                return $data;
            }
        }
        return FALSE;   
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