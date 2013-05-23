<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for Kohana View
 *
 * @package    OC
 * @category   View
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class View extends Kohana_View{
    
    /**
     * gets a cached fragment view
     * @param  string $name name to use in the cache should be unique
     * @param  string $file file view
     * @param  array $data 
     * @return string       
     */
    public static function fragment($name, $file = NULL, array $data = NULL)
    {
        $name = 'fragment_'.$name.'_'.i18n::lang().'_'.Theme::$theme.Theme::$skin; 

        if ( ($fragment = Core::cache($name))===NULL ) 
        {
            //if file is set and we dont have the cache we render.
            if ($file!==NULL)
            {
                $view = View::factory($file,$data);
                $fragment = $view->render();
                Core::cache($name,$fragment);
            }
            
        }   

        return $fragment;
    }
    
    /**
     * Sets the view filename. Overriide from origianl to liad from theme folder
     *
     *     $view->set_filename($file);
     *
     * @param   string  view filename
     * @return  View
     * @throws  View_Exception
     */
    public function set_filename($file)
    {
    	//folder loaded as module in the bootstrap :D
    	if (($path = Kohana::find_file(Theme::views_path(), $file)) === FALSE)
    	{
    		//in case view not found try to read from default theme
    		if (($path = Kohana::find_file(Theme::default_views_path(), $file)) === FALSE)
    		{
	    		//still not found :(, try from cascading system
	    		if (($path = Kohana::find_file('views', $file)) === FALSE)
	    		{
                    //d($file);
	    			throw new View_Exception('The requested view :file could not be found', array(
	    	                				':file' => $file,
	    			));
	    		}
    		}
    
    	}
    
    	// Store the file path locally
    	$this->_file = $path;
    
    	return $this;
    }

    
}
