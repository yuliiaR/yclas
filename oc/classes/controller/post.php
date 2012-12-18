<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Post extends Controller {
	
		/**
	 * 
	 * Display single post
	 * @throws HTTP_Exception_404
	 */
	public function action_view()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$post = new Model_Post();
			$post->where('seotitle','=', $seotitle)
				 ->limit(1)->find();
			
			if ($post->loaded())
			{

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/post/single',array('post'=>$post));
			}
			//not found in DB
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
			
		}
		else//this will never happen
		{
			//throw 404
			throw new HTTP_Exception_404();
		}
	}
	
	
	
	/**
	 * 
	 * new post
	 */
	public function action_index()
	{
		//template header
		$this->template->title           	= __('Publish new advertisement');
		$this->template->meta_description	= __('Publish new advertisement');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		//$this->template->scripts['footer'][]= 'js/autogrow-textarea.js';
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';
		
		
		//post submited
		if ($this->request->post())
		{
			//form validation
			//recaptcha validation, if recaptcha active
			
			//check account exists
				//if exists send email to activate post
				//if not exists create account and send email to confirm
				
			//save post data
			
			//save images, shrink and move to folder /upload/2012/11/25/pics/
			
		}
		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/new');
		$this->template->content->text = Text::bb2html($this->request->post('description'),TRUE);
	}



	/**
	 * Serp of Posts
	 */
	public function action_listing()
	{
		
		$slug_cat = $this->request->param('category',NULL);
		$slug_loc = $this->request->param('location',NULL);
		$page = $this->request->query('p',NULL);
		$this->template->bind('content', $content);

		//if everything null redirect to home??@todo
		
		// tmp code for printing all posts
		$cat = new Model_Category();
		$loc = new Model_Location();
		$sidebarCat = $cat->find_all(); // get all to print at sidebar view
		$sidebarLoc = $loc->find_all(); // get all to print at sidebar view

		//getting published posts
		$posts = new Model_Post();

		$posts->where('post.status', '=', Model_Post::STATUS_PUBLISHED);
		
		/*
		//SEO and filters
		if ($category->loaded() && $location->loaded())
		{
			$this->template->title = $category->name.', '.$location->name;
			$this->template->meta_keywords = $category->description.', '.$location->description;
			$this->template->meta_description = $category->description.', '.$location->description;
		}
		elseif ($location->loaded())
		{
			$this->template->title = $location->name;
			$this->template->meta_keywords = $location->description;
			$this->template->meta_description = $location->description;
		}
		elseif ($category->loaded())
		{
			$this->template->title = $category->name;
			$this->template->meta_keywords = $category->description;
			$this->template->meta_description = $category->description;
		}*/

    	//retreive category
		if ( $slug_cat !==NULL)
		{
			$category = new Model_Category();
			$_cat = $category->where('seoname', '=', $slug_cat)->limit(1)->find();

			if (!$category->loaded()){
				$slug_cat = NULL;		
			}
		}
		//retrieve location
		if ( $slug_loc !==NULL )
		{
			$location = new Model_Location();
			$_loc = $location->where('seoname', '=', $slug_loc)->limit(1)->find();
		 	
		 	if (!$location->loaded()){
		 		$slug_loc = NULL;
		 	}
		}
		
		$_search_post = ORM::factory('post');
		
		// get number of posts filtered, if no filtering, get all
		if($slug_cat && $slug_loc !== NULL){ //both parameters are valid, or combination of them is doesnt exist
			$_search_post->where('post.id_category', '=', $category->id_category)
                        		->and_where('post.id_location', '=', $location->id_location);
			
			if($_search_post->loaded()){
				$res_count = 0;
			}else{
            	$res_count = $_search_post->count_all();
            	echo $res_count;	
			} 
		}
		else if ($slug_cat !== NULL) // category provided
		{
			$_search_post->where('post.id_category', '=', $category->id_category);
			$res_count = $_search_post->count_all();	
		}
		else if($slug_cat == NULL && $slug_loc !== NULL) // category is missing
		{
			$_search_post->where('post.id_location', '=', $location->id_location);
			$res_count = $_search_post->count_all();

			Alert::set(Alert::ERROR, __('Category does\'t exists'));
		}
		else
		{
			$res_count = $posts->count_all();
		}
		
		/*
			PAGINATION 
		 */
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> 1
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                    'category'			=> $slug_cat,
                    'location'			=> $slug_loc,
    	    ));

	        $pagination->title($this->template->title);
	     
	    // END PAGINATION
	        
				//filter category
				if ($slug_loc && $slug_cat !== NULL)
				{
					$posts = $posts->where('post.id_category','=',$category->id_category)
									->and_where('post.id_location','=', $location->id_location)
									->order_by('created','desc')
                                	->limit($pagination->items_per_page)
                                	->offset($pagination->offset)
                                	->find_all();
				}
				else if($slug_cat !== NULL)
				{
					$posts = $posts->where('post.id_category','=',$category->id_category)
									->order_by('created','desc')
                                	->limit($pagination->items_per_page)
                                	->offset($pagination->offset)
                                	->find_all();
				}	
				else if($slug_cat == NULL && $slug_loc !== NULL)
				{
					$posts = $posts->where('post.id_location','=',$location->id_location)
									->order_by('created','desc')
                    	            ->limit($pagination->items_per_page)
                    	            ->offset($pagination->offset)
                    	            ->find_all();
				}
				else 
				{
					$posts = $posts->order_by('created','desc')
                    	            ->limit($pagination->items_per_page)
                    	            ->offset($pagination->offset)
                    	            ->find_all();
				}

      
		}else{

			//trow 404 Exception
			throw new HTTP_Exception_404();
		}

		/*
			END PAGINATION 
		*/

	    	$this->template->content = View::factory('pages/post/listing',array('posts'			=> $posts,
																				'pagination' 	=> $pagination,
																				'sidebarCat'	=> $sidebarCat,
																				'sidebarLoc'	=> $sidebarLoc,
																				));
				
	}
	
	
} // End Post controller
