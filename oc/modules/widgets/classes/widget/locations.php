<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Locations widget reader
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Locations extends Widget
{

	public function __construct()
	{	

		$this->title = __('Locations');
		$this->description = __('Display Locations');

		$this->fields = array(	
						 		'locations_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Locations title displayed'),
						 		  						'default'   => __('Locations'),
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
        return parent::title($this->Locations_title);
    }
	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		$loc = new Model_Location();
        $loc = $loc->where('id_location','!=',1)->order_by('order','asc')->cached()->find_all();
		$this->loc_items = $loc;

        $this->cat_seoname = NULL;
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
                $this->cat_seoname = Controller::$category->seoname;
        }
	}


}