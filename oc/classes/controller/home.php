<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller {

	public function action_index()
	{

	    //template header
	    $this->template->title            = 'welcome';
	    $this->template->meta_keywords    = 'keywords';
	    $this->template->meta_description = 'desc';
	    
	    //$this->template->header='';

	    //setting main view/template and render pages
	   
        //$ads = ORM::factory('ad');
        $ads = new Model_Ad();
        $display_ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
                        ->order_by('published','desc')
                        ->limit(Theme::get('num_home_latest_ads', 4))
                        ->cached()->find_all();
        

		$categs = Model_Category::get_category_count();
	
        $this->template->bind('content', $content);
        
        $this->template->content = View::factory('pages/home',array('ads'=>$display_ads, 
        															'categs'=>$categs,
        															));
		
	}

	// public function action_parent_cat() 

} // End Welcome
