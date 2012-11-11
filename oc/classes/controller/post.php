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
	 * Serp of Posts
	 */
	public function action_listing()
	{
		$slug_cat = $this->request->param('category',NULL);
		$slug_loc = $this->request->param('location',NULL);
		$page	  = $this->request->query('p',NULL);
		
		//if everything null redirect to home??@todo
		
		//getting published posts
		$posts = new Model_Post();
		$posts  /*->join('categories')
				->on('categories.id_category','=','post.id_category')
				->join('locations')
				->on('locations.id_location','=','post.id_location')*/
				->where('post.status', '=', Model_Post::STATUS_PUBLISHED);
		
		//retrieve category
		if ( $slug_cat !==NULL )
		{
			$category = new Model_Category();
			$category->where('seoname', '=', $slug_cat)->limit(1)->find();
			
			//filter category
			if ($category->loaded())
				$posts->where('post.id_category','=',$category->id_category);
		}
		
		//retrieve location
		if ( $slug_loc !==NULL )
		{
			$location = new Model_Location();
			$location->where('post.seoname', '=', $slug_loc)->limit(1)->find();
			
			//filter location
			if ($location->loaded())
				$posts->where('id_location','=',$location->id_location);
		}
		
		/*
		//SEO and filters
		if ($category->loaded() && $location->loaded())
		{
			$this->template->title            = $category->name.', '.$location->name;
			$this->template->meta_keywords    = $category->description.', '.$location->description;
			$this->template->meta_description = $category->description.', '.$location->description;
		}
		elseif ($location->loaded())
		{
			$this->template->title            = $location->name;
			$this->template->meta_keywords    = $location->description;
			$this->template->meta_description = $location->description;
		}
		elseif ($category->loaded())
		{
		    $this->template->title            = $category->name;
		    $this->template->meta_keywords    = $category->description;
		    $this->template->meta_description = $category->description;
		}*/
		
	    		
        
        //retieve posts
        //d($posts);
        $posts = $posts->cached(5)->limit(10)->find_all();
        		
		/*if (!count($posts)) //not found in DB
				throw new HTTP_Exception_404();*/
			
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/listing',array('posts'=>$posts));
		
		
	}
	
	/**
	 * 
	 * new post
	 */
	public function action_new()
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

} // End Post controller
