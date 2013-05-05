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
    public static function scripts($scripts, $type = 'header' , $theme = NULL)
    {
    	$ret = '';
    
    	if (isset($scripts[$type])===TRUE)
    	{
    		foreach($scripts[$type] as $file)
    		{
    			$file = self::public_path($file, $theme);
    			$ret .= HTML::script($file, NULL, TRUE);
    		}
    	}
    	return $ret;
    }
    
    //@todo merge and minify, vendor
    public static function styles($styles , $theme = NULL)
    {
    	$ret = '';
    	foreach($styles as $file => $type)
    	{
    		$file = self::public_path($file, $theme);
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
     * @param string $theme optional
     * @return string
     */
    public static function public_path($file, $theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

    	//not external file we need the public link
    	if (!Valid::url($file))
    	{
    		//@todo add a hook here in case we want to use a CDN
    		return URL::base('http').'themes'.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.$file;
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
        return DOCROOT.'themes'.DIRECTORY_SEPARATOR.$theme;
    }

    /**
     * get the full path folder for the theme init.php file
     * @param  string $theme 
     * @return string        
     */
    public static function theme_init_path($theme = 'default')
    {
        return self::theme_folder($theme).DIRECTORY_SEPARATOR.'init.php';
    }


    /**
     * detect if visitor browser is mobile
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
            if (file_exists(self::theme_init_path(Core::get('theme'))))
                $theme = Core::get('theme');
        }
        elseif (Cookie::get('theme',NULL)!==NULL)
        {
            if (file_exists(self::theme_init_path(Cookie::get('theme'))))
                $theme = Cookie::get('theme');
        }
        // elseif(self::is_mobile())
        // {
        //     if (file_exists(self::theme_init_path('mobile')))
        //         $theme = 'mobile';
        // }
        else
        {
            if (file_exists(self::theme_init_path(Core::config('appearance.theme'))))
                $theme = Core::config('appearance.theme');
        }

        //we save the cookie for next time
        Cookie::set('theme', $theme, Core::config('auth.lifetime'));

    	//load theme init.php like in module, to load default JS and CSS for example
    	self::$theme = $theme;
    	Kohana::load(self::theme_init_path(self::$theme));
    	
        self::load();
            	
    }

    /**
     * sets the theme we need to use in front
     * @param string $theme 
     */
    public static function set_theme($theme)
    {
        //we check the theme exists and it's correct
        if (!file_exists($file = self::theme_init_path($theme)))
            return FALSE;

        // save theme to DB
        $conf = new Model_Config();
        $conf->where('group_name','=','appearance')
                    ->where('config_key','=','theme')
                    ->limit(1)->find();

        if (!$conf->loaded())
        {
            $conf->group_name = 'appearance';
            $conf->config_key = 'theme';
        }
        
        $conf->config_value = $theme;

        try 
        {
            Cookie::set('theme', $theme, Core::config('auth.lifetime'));
            $conf->save();
            return TRUE;
        } 
        catch (Exception $e) 
        {
            throw new HTTP_Exception_500();     
        }   

    }
    
    /**
     * Read the folder /themes/ for themes
     */
    public static function get_installed_themes()
    {
        //read folders in theme folder
        $folder = DOCROOT.'themes';

        $themes = array();

        //check directory for themes
        foreach (new DirectoryIterator($folder) as $file) 
        {
            if($file->isDir() AND !$file->isDot())
            {
                if ( ($info = self::get_theme_info($file->getFilename())) !==FALSE)
                    $themes[$file->getFilename()] = $info;
            }
        }

        return $themes;
    }

    /**
     * returns the info regarding to the theme stores at init.php
     * @param  string $theme theme to search info
     * @return array        
     */
    public static function get_theme_info($theme = NULL)
    {
        if ($theme === NULL)
            $theme = self::$theme;

        if (!file_exists($file = self::theme_init_path($theme)))
            return FALSE;

        return Core::get_file_data($file , array(
            'Name'        => 'Theme Name',
            'ThemeURI'    => 'Theme URI',
            'ThemeDemo'   => 'Theme Demo',
            'Description' => 'Description',
            'Author'      => 'Author',
            'AuthorURI'   => 'Author URI',
            'Version'     => 'Version',
            'License'     => 'License',
            'Tags'        => 'Tags',
        )); 
    }

    /**
     * returns the screenshot
     * @param  string $theme theme to search info
     * @return array        
     */
    public static function get_theme_screenshot($theme = NULL)
    {

        if ($theme === NULL)
            $theme = self::$theme;

        $file = self::theme_folder($theme).DIRECTORY_SEPARATOR.'screenshot.png';

        if (file_exists($file))
            return self::public_path('screenshot.png',$theme);

        return FALSE;
    }


    /**
     * this belongs to the admin, so needs to be loaded no matter, the theme. not a good place here?? not nice...
     * generates a link used in the admin HTML
     * @param  string $name       translated name in the A
     * @param  string $controller
     * @param  string $action     
     * @param  string $route      
     * @param  string $icon         class name of bootstrap icon to append with nav-link 
     */
    public static function admin_link($name,$controller,$action='index',$route='oc-panel', $icon=NULL)
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
     * to know if we loaded...
     * @var bool
     */
    public static $loaded = FALSE;

    /**
     * loads the theme data from the config
     * @return void 
     */
    public static function load()
    {   
        if (!self::$loaded)
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
            self::$loaded = TRUE;
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
     * @param mixed default value in case is not set
     * @return mixed       
     */
    public static function get($name, $default = NULL)
    {
        return (array_key_exists($name, self::$data)) ? self::$data[$name] : $default;
    }


   

}
