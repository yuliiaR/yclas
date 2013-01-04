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
		$ads = new Model_Ad();

		$ads /*->join('categories')
				->on('categories.id_category','=','ad.id_category')
				->join('locations')
				->on('locations.id_location','=','ad.id_location')*/
				->where('ad.status', '=', Model_Ad::STATUS_PUBLISHED);
		
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
		if ( $slug_cat !==NULL )
		{
			$category = new Model_Category();
			$category->where('seoname', '=', $slug_cat)->limit(1)->find();
			
			//filter category
			$filter = $ads->where('ad.id_category','=',$category->id_category);
			$res_count_categories = $filter->count_all();	
		}
		else $res_count_categories = 0;
		
		//retrieve location
		if ( $slug_loc !==NULL )
		{
			$location = new Model_Location();
		 	$location->where('seoname', '=', $slug_loc)->limit(1)->find();
			
			//filter location
		 	$filter = $ads->where('ad.id_location','=',$location->id_location);
		 	$res_count_locations = $filter->count_all();
		}
		else $res_count_locations = 0;
		 
		 
		if (($res_count_locations + $res_count_categories) == 0)
		 	$res_count = $ads->count_all();	
		else 
		 	$res_count = max(array($res_count_locations, $res_count_categories));
			
		
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
    	    ));

	        $pagination->title($this->template->title);

	    // END PAGINATION
	        
			//retrieve category >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			
			if ( $slug_cat !==NULL )
			{
				
				
				//filter category
				if ($category->loaded() && $slug_loc !== NULL)
				{
					$ads = $ads->where('ad.id_category','=',$category->id_category)
									->and_where('ad.id_location','=', $location->id_location)
									->order_by('created','desc')
                                	->limit($pagination->items_per_page)
                                	->offset($pagination->offset)
                                	->find_all();
				}
				else if($category->loaded())
				{
					$ads = $ads->where('ad.id_category','=',$category->id_category)
									->order_by('created','desc')
                                	->limit($pagination->items_per_page)
                                	->offset($pagination->offset)
                                	->find_all();
				}
			
			}
			else
			{
				$ads = $ads->order_by('created','desc')
                                ->limit($pagination->items_per_page)
                                ->offset($pagination->offset)
                                ->find_all();
			}

      
		}

		/*
			END PAGINATION 
		*/

	    	$this->template->content = View::factory('pages/post/listing',array('ads'			=> $ads,
																				'pagination' 	=> $pagination,
																				));		
	}
	
	
} // End Post controller
