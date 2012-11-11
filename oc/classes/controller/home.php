<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller {

	public function action_index()
	{
	    
	    //template header
	    $this->template->title            = 'welcome';
	    $this->template->meta_keywords    = 'keywords';
	    $this->template->meta_description = 'desc';
	
	    
	    //$this->template->header='';

	    //setting main view/template and render page

	   
        //$posts = ORM::factory('post');
        $posts = new Model_Post();
        $posts  ->where('status', '=', 1)
                ->cached(5)
                ->limit(10);
        $res = print_r($posts->find(),1);
        
        
        $this->template->bind('content', $content);
        
        $this->template->content = View::factory('pages/home',array('posts'=>$res));
		
	}

} // End Welcome
