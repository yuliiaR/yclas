<?php defined('SYSPATH') or die('No direct script access.');
/**
 * theme functionality
 *
 * @package    OC
 * @category   Theme
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Theme {
    
    
    private static $theme       = 'default';
    private static $views_path  = 'views';
    public  static $scripts     = array();
    public  static $styles      = array();
    
    
    //@todo merge and minify
    public static function scripts($scripts,$type='header')
    {
    	$ret = '';
    
    	if (isset($scripts[$type])===TRUE)
    	{
    		foreach($scripts[$type] as $file)
    		{
    			$file = self::public_path($file);
    			$ret .= HTML::script($file, NULL, TRUE);
    		}
    	}
    	return $ret;
    }
    
    //@todo merge and minify, vendor
    public static function styles($styles)
    {
    	$ret = '';
    	foreach($styles as $file => $type)
    	{
    		$file = self::public_path($file);
    		$ret .= HTML::style($file, array('media' => $type));
    	}
    	return $ret;
    }
    
    /**
     *
     * gets the where the views are located in the default theme
     * @return string path
     *
     */
    public static function default_views_path()
    {
    	return 'default'.DIRECTORY_SEPARATOR.self::$views_path;
    }
    
    /**
     *
     * gets the where the views are located in the theme
     * @return string path
     *
     */
    public static function views_path()
    {
    	return self::$theme.DIRECTORY_SEPARATOR.self::$views_path;
    }
    
    /**
     *
     * given a file returns it's public path relative to the selected theme
     * @param string $file
     * @return string
     */
    public static function public_path($file)
    {
    	//not external file we need the public link
    	if (!Valid::url($file))
    	{
    		//@todo add a hook here in case we want to use a CDN
    		return URL::base('http').'themes'.DIRECTORY_SEPARATOR.self::$theme.DIRECTORY_SEPARATOR.$file;
    	}
    	 
    	//seems an external url
    	return $file;
    }
    
    /**
     * get the full path folder for the theme
     * @param  string $theme 
     * @return string        
     */
    public static function theme_folder($theme = 'default')
    {
        return DOCROOT.'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'init.php';
    }

    /**
     * detect if browser is mobile
     * @return boolean
     */
    public static function is_mobile()
    {
        //@todo decide how to do this when we have the mobile theme
        if (Core::config('general.mobile') == 0)
        {
            return FALSE;
        }
        //we check if we are forcing not to show mobile
        elseif(Core::get('mobile')==0)
        {
            Cookie::set('mobile',0, Core::config('auth.lifetime'));
            return FALSE;
        }
        //they want to show the mobile
        elseif (Core::get('mobile')==1 OR Cookie::get('mobile')==1)
        {
            Cookie::set('mobile',1, Core::config('auth.lifetime'));
            return TRUE;
        }
        //none of this scenarios try to detect if ismobile
        else
        {
            require Kohana::find_file('vendor', 'Mobile-Detect/Mobile_Detect',EXT);

            $detect = new Mobile_Detect();
            if ($detect->isMobile() AND ! $detect->isTablet())
            {
                Cookie::set('mobile',1,Core::config('auth.lifetime'));
                return TRUE;
            }
            else
                return FALSE;
        }
        
    }


    /**
     * Initialization of the theme that we want to see.
     *
     */
    public static function initialize($theme = 'default')
    {
    	/**
    	 * Get the theme
    	 * 1st by get
    	 * 2nd by cookie
    	 * 3rd mobile (only if mobile is ON)
    	 * 4th the one seted in config
    	 */
        if (Core::get('theme', NULL)!==NULL)
        {
            if (file_exists(self::theme_folder(Core::get('theme'))))
                $theme = Core::get('theme');
        }
        elseif (Cookie::get('theme',NULL)!==NULL)
        {
            if (file_exists(self::theme_folder(Cookie::get('theme'))))
                $theme = Cookie::get('theme');
        }
        elseif(self::is_mobile())
        {
            if (file_exists(self::theme_folder('mobile')))
                $theme = 'mobile';
        }
        else
        {
            if (file_exists(self::theme_folder(Core::config('appearance.theme'))))
                $theme = Core::config('appearance.theme');
        }

        //we save the cookie for next time
        Cookie::set('theme', $theme, Core::config('auth.lifetime'));

    	//load theme init.php like in module, to load default JS and CSS for example
    	self::$theme = $theme;
    	Kohana::load(self::theme_folder(self::$theme));
    	
    
    	//this is much slower, but more flexible...mmmm
    	/*if (($init_theme = Kohana::find_file(self::$path,'init'))){
    	require_once $init_theme;
    	}*/
    }
    
}
