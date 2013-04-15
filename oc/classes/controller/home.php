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
        $display_ads = $ads->where('status', '=', 1)->limit(4)->find_all();
        
        $categ = new Model_Category();

        $all_categories = $categ->find_all();

		$children_categ = $categ->get_category_children();
	
		// return image path 
		$img_path = array();
		$image_exists = new Model_Ad();
		// generates image roots 
		foreach ($display_ads as $a) {

			if(!is_dir($image_exists->_gen_img_path($a->seotitle, $a->created)))
			{
				$a->has_images = 0;
				try {
					$a->save();
				} catch (Exception $e) {
					echo $e;
				}
			}
			
			$rep = new Controller_Ad($this->request, $this->response);
			if(is_array($path = $rep->_image_path($a)))
			{
				$path = $rep->_image_path($a);
				$img_path[$a->seotitle] = $path;
			}
			else
			{
				$path = NULL;
				$img_path[$a->seotitle] = $path;	
			} 
		}
		// d($img_path);
        $this->template->bind('content', $content);
        
        $this->template->content = View::factory('pages/home',array('ads'=>$display_ads, 
        															'categ'=>$all_categories,
        															'img_path'=>$img_path,
        															'children_categ'=>$children_categ));
		
	}

	// public function action_parent_cat() 

} // End Welcome
