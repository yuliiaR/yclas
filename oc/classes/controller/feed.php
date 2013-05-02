<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Feed extends Controller {

	public function action_index()
	{
        $this->auto_render = FALSE;

		$info = array(
						'title' 	=> 'RSS '.Core::config('general.site_name'),
						'pubDate' => date("D, d M Y H:i:s T"),
						'description' => __('Latest published'),
						'generator' 	=> 'Open Classifieds',
		); 
  		
  		$items = array();

  		//last ads, you can modify this value at: general.feed_elements
        $ads = DB::select('a.seotitle')
                ->select(array('c.seoname','category'),'a.title','a.description','a.published')
                ->from(array('ads', 'a'))
                ->join(array('categories', 'c'),'INNER')
                ->on('a.id_category','=','c.id_category')
                ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                ->limit(Core::config('general.feed_elements'));

        //filter by category aor location
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
                $ads->where('a.id_category','=',Controller::$category->id_category);
        }

        if (Controller::$location!==NULL)
        {
            if (Controller::$location->loaded())
                $ads->where('a.id_location','=',Controller::$location->id_location);
        }

        $ads = $ads->as_object()->cached()->execute();

        foreach($ads as $a)
        {
            $url= Route::url('ad',  array('category'=>$a->category,'seotitle'=>$a->seotitle));

            $items[] = array(
			                	'title' 	=> $a->title,
			                	'link' 	=> $url,
			                	'pubDate' => Date::mysql2unix($a->published),
			                	'description' => Text::removebbcode($a->description),
			              );
        }
  
  		$xml = Feed::create($info, $items);

  		$this->response->headers('Content-type','text/xml');
        $this->response->body($xml);
	
	}

    /**
     * after does nothing since we send an XML
     */
    public function after(){}


} // End feed
