<?php 
$d = dir('/upload/13/01/15/ca16');

$entry = $d->read('1');
echo "123";
$image = $entry;
echo $image;
// public function action_list_logic()
// 	{
// 		/// comon
// 		$slug_cat = $this->request->param('category',NULL);
// 		$slug_loc = $this->request->param('location',NULL);
// 		$page = $this->request->query('p',NULL);
		

// 		//if everything null redirect to home??@todo
		
// 		// tmp code for printing all ads
// 		$cat = new Model_Category();
// 		$loc = new Model_Location();
// 		$sidebarCat = $cat->find_all(); // get all to print at sidebar view
// 		$sidebarLoc = $loc->find_all(); // get all to print at sidebar view

// 		//getting published ads
// 		$ads = new Model_Ad();

// 		$ads->where('ad.status', '=', Model_Ad::STATUS_PUBLISHED);
// 		$ads->find_all();
	
// 		/*
// 		//SEO and filters
// 		if ($category->loaded() && $location->loaded())
// 		{
// 			$this->template->title = $category->name.', '.$location->name;
// 			$this->template->meta_keywords = $category->description.', '.$location->description;
// 			$this->template->meta_description = $category->description.', '.$location->description;
// 		}
// 		elseif ($location->loaded())
// 		{
// 			$this->template->title = $location->name;
// 			$this->template->meta_keywords = $location->description;
// 			$this->template->meta_description = $location->description;
// 		}
// 		elseif ($category->loaded())
// 		{
// 			$this->template->title = $category->name;
// 			$this->template->meta_keywords = $category->description;
// 			$this->template->meta_description = $category->description;
// 		}*/

//     	//retreive category
// 		if ( $slug_cat !==NULL)
// 		{
// 			$category = new Model_Category();
// 			$_cat = $category->where('seoname', '=', $slug_cat)->limit(1)->find();

// 			if (!$category->loaded()){
// 				$slug_cat = NULL;		
// 			}
// 		}
// 		//retrieve location
// 		if ( $slug_loc !==NULL )
// 		{
// 			$location = new Model_Location();
// 			$_loc = $location->where('seoname', '=', $slug_loc)->limit(1)->find();
		 	
// 		 	if (!$location->loaded()){
// 		 		$slug_loc = NULL;
// 		 	}
// 		}
		
// 		$_search_ad = ORM::factory('ad');
		
// 		// get number of ads filtered, if no filtering, get all
// 		if($slug_cat && $slug_loc !== NULL){ //both parameters are valid, or combination of them is doesnt exist
// 			$_search_ad->where('ad.id_category', '=', $category->id_category)
//                         		->and_where('ad.id_location', '=', $location->id_location);
			
// 			if($_search_ad->loaded()){
// 				$res_count = 0;
// 			}else{
//             	$res_count = $_search_ad->count_all();
//             	echo 'res_count'.$res_count.' :';	
// 			} 
// 		}
// 		else if ($slug_cat !== NULL) // category provided
// 		{
// 			$_search_ad->where('ad.id_category', '=', $category->id_category);
// 			$res_count = $_search_ad->count_all();	
// 		}
// 		else if($slug_cat == NULL && $slug_loc !== NULL) // category is missing
// 		{
// 			$_search_ad->where('ad.id_location', '=', $location->id_location);
// 			$res_count = $_search_ad->count_all();

// 			Alert::set(Alert::ERROR, __('Category does\'t exists'));
// 		}
// 		else
// 		{
// 			$res_count = $ads->count_all();
// 		}
		
// 		/*
// 			PAGINATION 
// 		 */
		
// 		if ($res_count > 0)
// 		{

// 			$pagination = Pagination::factory(array(
//                     'view'           	=> 'pagination',
//                     'total_items'    	=> $res_count,
//                     'items_per_page' 	=> 5
//      	    ))->route_params(array(
//                     'controller' 		=> $this->request->controller(),
//                     'action'      		=> $this->request->action(),
//                     'category'			=> $slug_cat,
//                     'location'			=> $slug_loc,
//     	    ));
  
// 			//filter category
// 			if ($slug_loc && $slug_cat !== NULL)
// 			{
// 				$ads = $ads->where('ad.id_category','=',$category->id_category)
// 								->and_where('ad.id_location','=', $location->id_location)
// 								->order_by('created','desc')
//                             	->limit($pagination->items_per_page)
//                             	->offset($pagination->offset)
//                             	->find_all();
// 			}
// 			else if($slug_cat !== NULL)
// 			{
// 				$ads = $ads->where('ad.id_category','=',$category->id_category)
// 								->order_by('created','desc')
//                             	->limit($pagination->items_per_page)
//                             	->offset($pagination->offset)
//                             	->find_all();
// 			}	
// 			else if($slug_cat == NULL && $slug_loc !== NULL)
// 			{
// 				$ads = $ads->where('ad.id_location','=',$location->id_location)
// 								->order_by('created','desc')
//                 	            ->limit($pagination->items_per_page)
//                 	            ->offset($pagination->offset)
//                 	            ->find_all();
// 			}
// 			else 
// 			{
// 				$ads = $ads->order_by('created','desc')
//                 	            ->limit($pagination->items_per_page)
//                 	            ->offset($pagination->offset)
//                 	            ->find_all();
// 			}

// 		}
// 		else
// 		{
// 			//trow 404 Exception
// 			throw new HTTP_Exception_404();
// 		}
// 		return array('ads'=>$ads,'pagination'=>$pagination,'sidebarCat'=>$sidebarCat,'sidebarLoc'=>$sidebarLoc);
// 	}
	


// 	public function search()
// 	{
// 	$ads = new Model_Ad();

// 		$ads->where('ad.status', '=', Model_Ad::STATUS_PUBLISHED);
// 		$ads->find_all();
// 		$_search_ad = ORM::factory('ad');
// 		$res_count = $ads->count_all();
// 		if ($res_count > 0)
// 		{

// 			$pagination = Pagination::factory(array(
//                     'view'           	=> 'pagination',
//                     'total_items'    	=> $res_count,
//                     'items_per_page' 	=> 5
//      	    ))->route_params(array(
//                     'controller' 		=> $this->request->controller(),
//                     'action'      		=> $this->request->action(),
//                     'category'			=> $slug_cat,
//                     'location'			=> $slug_loc,
//     	    ));
//     	    $ads = $ads->order_by('created','desc')
//                 	            ->limit($pagination->items_per_page)
//                 	            ->offset($pagination->offset)
//                 	            ->find_all();
// 			}
// 		else
// 		{
// 			//trow 404 Exception
// 			throw new HTTP_Exception_404();
// 		}
// 		return array('ads'=>$ads,'pagination'=>$pagination);

// 	}
?>
