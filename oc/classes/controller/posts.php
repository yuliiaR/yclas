<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Posts extends Controller {
	
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

		//getting published posts
		$posts = new Model_Post();

		$posts /*->join('categories')
				->on('categories.id_category','=','post.id_category')
				->join('locations')
				->on('locations.id_location','=','post.id_location')*/
				->where('post.status', '=', Model_Post::STATUS_PUBLISHED);

		

		
		
		// END RETRIEVE CATEGORY >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		
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
    
		if ( $slug_cat !==NULL )
		{
			$category = new Model_Category();
			$category->where('seoname', '=', $slug_cat)->limit(1)->find();
			$filter = $posts->where('post.id_category','=',$category->id_category);
			$res_count_categories = $filter->count_all();	
		}
		else
		{
			//count posts
			$res_count_categories = $posts->count_all(); //returns number of posts
		
		}
		//retrieve location
		 if ( $slug_loc !==NULL )
		 {
		 	$location = new Model_Location();
		 	$location->where('seoname', '=', $slug_loc)->limit(1)->find();
		//filter location
		 	$filter = $posts->where('post.id_location','=',$location->id_location);
		 	$res_count_locations = $filter->count_all();
		 }
		 else
		 {
		 	$res_count_locations = $posts->count_all();
		 }
		$res_count = max(array($res_count_locations, $res_count_categories));
		/*
			PAGINATION 
		 */
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> 2
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
    	    ));

	        $pagination->title($this->template->title);

	       
	        
			//retrieve category >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			if ( $slug_cat !==NULL )
			{
				
				
				//filter category
				if ($category->loaded() && $slug_loc !== NULL)
				{
					$posts = $posts->where('post.id_category','=',$category->id_category)
									->and_where('post.id_location','=', $location->id_location)
									->order_by('created','desc')
                                	->limit($pagination->items_per_page)
                                	->offset($pagination->offset)
                                	->find_all();
				}
				else if($category->loaded())
				{
					$posts = $posts->where('post.id_category','=',$category->id_category)
									->order_by('created','desc')
                                	->limit($pagination->items_per_page)
                                	->offset($pagination->offset)
                                	->find_all();
				}
			
			}
			else
			{
				$posts = $posts->order_by('created','desc')
                                ->limit($pagination->items_per_page)
                                ->offset($pagination->offset)
                                ->find_all();
			}

      
		}

		/*
			END PAGINATION 
		*/

	    	$this->template->content = View::factory('pages/post/listing',array('posts'		=> $posts,
																			'pagination' 	=> $pagination,
																			));		
	}
	
	
} // End Post controller
