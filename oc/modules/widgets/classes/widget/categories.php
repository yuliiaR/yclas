<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Categories widget reader
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Categories extends Widget
{

	public function __construct()
	{	

		$this->title = __('Categories');
		$this->description = __('Display categories');

		$this->fields = array(	
						 		'categories_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Categories title displayed'),
						 		  						'default'   => __('Categories'),
														'required'	=> FALSE),
						 		);
	}


    /**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->categories_title);
    }
	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		$cat = new Model_Category();
        $cat = $cat->where('id_category','!=',1)->order_by('order','asc')->cached()->find_all();
		$this->cat_items = $cat;

        $this->loc_seoname = NULL;
        if (Controller::$location!==NULL)
        {
            if (Controller::$location->loaded())
                $this->loc_seoname = Controller::$location->seoname;
        }

	}


}