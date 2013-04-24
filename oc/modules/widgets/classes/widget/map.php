<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Google Maps widget
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */


class Widget_Map extends Widget
{

	public function __construct()
	{	

		$this->title = __('Map');
		$this->description = __('Google Maps with ads');

		$this->fields = array(	
								'map_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Map title displayed'),
														'required'	=> FALSE),
                                'map_height'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Map height'),
                                                        'default'   => '200px',
                                                        'required'  => FALSE),
								
						 		);
	}


    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        require_once Kohana::find_file('vendor', 'php-googlemap/GoogleMap','php');
        $map = new GoogleMapAPI('map_widget'.$this->widget_name);
        $map->setWidth('100%');
        $map->setHeight($this->map_height);
        $map->disableSidebar();
        $map->disableMapControls();
        $map->disableTypeControls();
        $map->disableScaleControl();
        $map->disableZoomEncompass();
        $map->setMapType('map');

        //last ads, you can modify this value at: general.feed_elements
        $ads = DB::select('a.seotitle')
                ->select(array('c.seoname','category'),'a.title','a.published','a.address')
                ->from(array('ads', 'a'))
                ->join(array('categories', 'c'),'INNER')
                ->on('a.id_category','=','c.id_category')
                ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                ->where('a.address','IS NOT',NULL)
                ->limit(Core::config('general.feed_elements'))
                ->as_object()
                ->cached()
                ->execute();

                
        foreach($ads as $a)
        {
            //d($a);
            $url= Route::url('ad',  array('category'=>$a->category,'seotitle'=>$a->seotitle));
            $map->addMarkerByAddress($a->address, $a->title, HTML::anchor($url, $a->title));
        }

        $this->map = $map;
    }


    /**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->map_title);
    }

}