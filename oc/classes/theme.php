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
    
    
    public static $theme        = 'default';
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
     * gets where the views are located in the default theme
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
    	
        self::load();
            	
    }




    /**
     * All the Custom options for the theme goes here
     */
    

    /**
     * array option the theme have, defined in the theme/ init.php
     * ex:
     * array(     'rss_items' => array( 'type'      => 'numeric',
     *                                                  'display'   => 'select',
     *                                                  'label'     => __('# items to display'),
     *                                                  'options'   => range(1,10), 
     *                                                  'required'  => TRUE),);
     * @var array
     */
    public static $options = array();


    /**
     * data stored for each field
     * @var array
     */
    public static $data = array();


    /**
     * loads the theme data from the config
     * @return void 
     */
    public static function load()
    {   
        //search for theme config
        $theme_data = core::config('theme.'.self::$theme);

        //found and with data!
        if($theme_data!==NULL AND !empty($theme_data) AND $theme_data !== '[]')
        { 
            self::$data = json_decode($theme_data, TRUE);
        }
        ///save empty with default values
        else
        {
            //we set the array with empty values or the default in the option attributes
            foreach (self::$options as $field => $attributes) 
            {
                self::$data[$field] = (isset($attributes['default']))?$attributes['default']:'';
            }
            self::save();
        }

    }


    /**
     * saves thme options as json 'theme.NAMETHEME' = array json
     * @return void 
     */
    public static function save()
    {

        // save theme to DB
        $conf = new Model_Config();
        $conf->where('group_name','=','theme')
                    ->where('config_key','=',self::$theme)
                    ->limit(1)->find();

        if (!$conf->loaded())
        {
            $conf->group_name = 'theme';
            $conf->config_key = self::$theme;
        }
        
        $conf->config_value = json_encode(self::$data);

        try 
        {
            $conf->save();
        } 
        catch (Exception $e) 
        {
            throw new HTTP_Exception_500();     
        }   
    }

    /**
     * to know if we need to render form for example
     * @return boolean 
     */
    public static function has_options()
    {
        return (count(self::$data)>0)? TRUE:FALSE;
    }

    /**
     * get from data array
     * @param  string $name key
     * @return mixed       
     */
    public static function get($name)
    {
        return (array_key_exists($name, self::$data)) ? self::$data[$name] : NULL;
    }
    

}
