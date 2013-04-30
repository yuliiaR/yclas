<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Front end controller for OC app
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Controller extends Kohana_Controller
{
    public $template = 'main';

    /**
     * @var  boolean  auto render template
     */
    public $auto_render = TRUE;

    /**
     * global category get form controller so we can access form anywhere like Controller::$category;
     * @var Model_Category
     */
    public static $category = NULL;

    /**
     * global Location get form controller so we can access form anywhere like Controller::$location;
     * @var Model_Location
     */
    public static $location = NULL;
    
    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before($template = NULL)
    {
        parent::before();
        if($this->auto_render===TRUE)
        {
        	// Load the template
            if ($template!==NULL)
                $this->template= $template; 
        	$this->template = View::factory($this->template);
        	
            // Initialize empty values
            $this->template->title            = core::config('general.site_name');
            $this->template->meta_keywords    = '';
            $this->template->meta_description = '';
            $this->template->meta_copywrite   = 'Open Classifieds '.Core::version;
            $this->template->header           = View::factory('header');
            $this->template->content          = '';
            $this->template->footer           = View::factory('footer');
            $this->template->styles           = array();
            $this->template->scripts          = array();

            /**
             * selected category
             */
            if($this->request->param('category',NULL) != 'all' )
            {
                $slug_cat   = new Model_Category();
                $seo_cat = $slug_cat->where('seoname', '=', $this->request->param('category'))->limit(1)->find();
                if ($seo_cat->loaded())
                    self::$category = $seo_cat;
            }
            
            /**
             * selected location
             */
            if($this->request->param('location',NULL) != NULL || $this->request->param('location') != 'all')
            {
                $slug_loc   = new Model_Location();
                $seo_loc = $slug_loc->where('seoname', '=', $this->request->param('location'))->limit(1)->find();
                
                if ($seo_loc->loaded())
                    self::$location = $seo_loc;
            }


        }
    }
    
    /**
     * Fill in default values for our properties before rendering the output.
     */
    public function after()
    {
    	parent::after();
    	if ($this->auto_render === TRUE)
    	{
    		// Add defaults to template variables.
    		$this->template->styles  = array_reverse(array_merge($this->template->styles, Theme::$styles));
    		$this->template->scripts = array_reverse(array_merge_recursive(Theme::$scripts,$this->template->scripts));
    		
    		$this->template->title.=' - '.core::config('general.site_name');

    		 //auto generate keywords and description from content
    		$seo = new seo($this->template->meta_description, Kohana::$charset);
    		
    		if ($this->template->meta_keywords == '')//not meta keywords given
    		{
    	       $this->template->meta_keywords = $seo->getKeyWords(12);
    		}

    		$this->template->meta_description = $seo->getMetaDescription(150);//die($this->template->meta_description);
    		
    		
    	}
    	$this->response->body($this->template->render());
       
    }
}