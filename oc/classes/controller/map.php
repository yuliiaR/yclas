<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Map extends Controller {

	public function action_index()
	{
        require_once Kohana::find_file('vendor', 'php-googlemap/GoogleMap','php');
  		
        $this->before('/pages/maps');

        $this->template->title  = __('Map').' '.Core::config('general.site_name');

        $map = new GoogleMapAPI();
        $map->setWidth('100%');
        $map->setHeight('100%');
        $map->disableSidebar();
        $map->setMapType('map');
        //$map->mobile = TRUE;

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
            $map->addMarkerByAddress($a->address, $a->title, HTML::anchor($url, $a->title) );
        }

        $this->template->map = $map;
	
	}



} // End map
