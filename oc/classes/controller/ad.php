<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	

	/**
	 * Publis all adver.-s without filter
	 */
	public function action_listing()
	{ 
		//$this->template->bind('content', $content);
		
		$advert 	= new Model_Ad();


        /*
        refactor this @todo
         */
        //////////////////////////////////////////////////
		$slug_cat 	= new Model_Category();
		$slug_loc 	= new Model_Location();

		$cat = $slug_cat->find_all();
		$loc = $slug_loc->find_all();
        //////////////////////////////////////////////////


        /**
         * we get the id of category and location from controller 
         */
        
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
                $cat_filter = Controller::$category->id_category;
        }
            
        if (Controller::$location!==NULL)
        {
            if (Controller::$location->loaded())
                $loc_filter = Controller::$location->id_location;
        }


		// user 
        if(Auth::instance()->get_user() == NULL)
		{
			$user = NULL;
		}
		else
		{
			$user = Auth::instance()->get_user();
		}

		
		if ($this->request->query('search'))
        {
        	$search_term = $this->request->query('search');
            
            $this->template->bind('content', $content);
			$this->template->content = View::factory('pages/ad/listing');

			$ads = $advert->where('status', '=', Model_Ad::STATUS_PUBLISHED);
			
	        //search term
	        if ( strlen($search_term)>2 )
	        {
	            $ads->where('title', 'like', '%'.$search_term.'%');
	        }

	        $res_count = count($ads);

	        $pagination = Pagination::factory(array(
	                    'view'           	=> 'pagination',
	                    'total_items'      	=> $res_count,
	                    'items_per_page' 	=> core::config('general.advertisements_per_page'),
	        ))->route_params(array(
	                    'controller' 		=> $this->request->controller(),
	                    'action'     	 	=> $this->request->action(),
	        ));

	        if ($res_count>0)
	        {
	        	$content->ads = $ads->order_by('created','desc')
	                                ->limit($pagination->items_per_page)
	                                ->offset($pagination->offset)
	                                ->find_all();
	                                   	            
	        } else $content->ads = NULL;
	       	
	       	// return image path to display in view 
			$thumb = array();
			$all_ads = $ads->where('title', 'like', '%'.$search_term.'%')->find_all();
			foreach ($all_ads as $a) 
			{

				if(!is_dir($a->gen_img_path($a->id_ad, $a->created)))
				{
					$a->has_images = 0;
					try 
					{
						$a->save();
					} catch (Exception $e) {
						echo $e;
					}
				}
				
				// search and create path of ads, fill array $thumb
				if(is_array($path = $this->image_path($a)))
				{
					foreach ($path as $key => $value) 
					{
						// hash tag to distinguish thumb from big image
						$hashtag = (core::config("theme_default.listing_images") != FALSE) ? strstr($value, 'thumb') : !strstr($value, 'thumb') ;

						if( $hashtag && strstr($value, '_1'))
						{
							$thumb[$a->seotitle] = $value;
						}
						else if (strstr($value, 'thumb') && !array_key_exists($a->seotitle, $thumb))
						{
							$thumb[$a->seotitle] = $value;	
						}
					}
					// case when there are no images , sanity check
					if(!isset($thumb[$a->seotitle]))
					{
						$thumb[$a->seotitle] = NULL;
					}
				}
				else
				{
					$path = NULL;
					$thumb[$a->seotitle] = $path;	
				} 

			}


			$content->pagination = $pagination->render();
			$content->cat = $cat;
			$content->loc = $loc;
			$content->thumb = $thumb;
			$content->user = $user;
			$content->img_path = NULL;
        }
        // advansed search with many parameters
        elseif($this->request->query('advert') 
        	 	|| $this->request->query('cat') 
        	 	|| $this->request->query('loc'))
        {
        	// variables 
        	$search_advert 	= $this->request->query('advert');
        	$search_cat 	= $this->request->query('cat');
        	$search_loc 	= $this->request->query('loc');
        	
        	$ads = $this->action_advansed_search($search_advert, $search_cat, $search_loc); // logic

        	$this->template->bind('content', $content);
			$this->template->content = View::factory('pages/ad/listing');

			$res_count = count($ads);

	        $pagination = Pagination::factory(array(
	                    'view'           	=> 'pagination',
	                    'total_items'      	=> $res_count,
	                    'items_per_page' 	=> core::config('general.advertisements_per_page'),
	        ))->route_params(array(
	                    'controller' 		=> $this->request->controller(),
	                    'action'     	 	=> $this->request->action(),
	        ));

	        $content->ads = $ads->order_by('created','desc')
	                   				   ->limit($pagination->items_per_page)
	                   				   ->offset($pagination->offset)
	                   				   ->find_all();

			// view variables
			$content->pagination = $pagination->render();
			$content->cat = $cat;
			$content->loc = $loc;
			$content->user = $user;
			$content->thumb = NULL;
			$content->img_path = NULL;
			
        }
        // list by category / location
        else
       	{ 
       		// d($cat_filter);

       		if($this->request->param('category') != 'all' && $this->request->param('location') != NULL)
       			$data = $this->list_logic($cat_filter, $loc_filter);
       		else if ($this->request->param('category') != 'all' && $this->request->param('location') == NULL)
       			$data = $this->list_logic($cat_filter);
       		else if ($this->request->param('category') == 'all' && $this->request->param('location') != "all")
       			$data = $this->list_logic( NULL ,  $loc_filter);
       		else
       			$data = $this->list_logic();
       			
	   		
			$this->template->bind('content', $content);
			$this->template->content = View::factory('pages/ad/listing',$data);
            $search_term = $this->request->param('search',NULL);
        }
 	}

 	/**
 	 * [list_logic Returnes arrays with necessary data to publis advert.-s]
 	 * @param  [string] $sort_by_cat [name of category] 
 	 * @param  [string] $sort_by_loc [name of location]
  	 * @return [array] [$ads, $pagination, $user, $image_path]
 	 */
	public function list_logic($sort_by_cat = NULL, $sort_by_loc = NULL)
	{
		$ads = new Model_Ad();
		
		$cat = new Model_Category($sort_by_cat);
		if($sort_by_cat == NULL) $categ = $cat->find_all(); else $categ = $cat->id_category;	
		
		
		$loc = new Model_Location($sort_by_loc);
		if($sort_by_loc == NULL) $locat = $loc->find_all(); else $locat = $loc->id_location;
		

		// if is sorted by category , or category + location
		if(is_numeric($locat))
		{
			if(!is_numeric($categ))
			{
				
				$res_count = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
							 ->and_where('id_location', '=', $locat)->count_all();	
			}
			else
			{
				$res_count = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
								 ->and_where('id_category', '=', $categ)
								 ->and_where('id_location', '=', $locat)->count_all();

			}
			
		} 
		else if(is_numeric($categ))
		{
			if(is_numeric($locat))
			{
				$res_count = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
								 ->and_where('id_category', '=', $categ)
								 ->and_where('id_location', '=', $locat)->count_all();
			}
			else
			{
				$res_count = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)->and_where('id_category', '=', $categ)->count_all();
			}
			
		}
		else
		{
			$res_count = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)->count_all();
		}


		// check if there are some advet.-s
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('general.advertisements_per_page'),
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                 
    	    )); //d($this->request->controller()." ".$this->request->action());
    	    
    		// cases depending on input provided    
     	    if(is_numeric($locat) )
     	    {
     	    	if(!is_numeric($categ))
     	    	{
     	    		$ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
	    					   ->where('id_location', '=', $locat)
	    					   ->order_by('published','desc')
            	               ->limit($pagination->items_per_page)
            	               ->offset($pagination->offset)
            	               ->find_all();
     	    	}
     	    	else
     	    	{
     	    		$ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
	    					   ->where('id_category', '=', $categ)
	    					   ->where('id_location', '=', $locat)
	    					   ->order_by('published','desc')
            	               ->limit($pagination->items_per_page)
            	               ->offset($pagination->offset)
            	               ->find_all();
     	    	}
     	    	
     	    }
    	    else if(is_numeric($categ))
			{
				if(is_numeric($locat))
				{
					$ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
	    					   ->where('id_category', '=', $categ)
	    					   ->where('id_location', '=', $locat)
	    					   ->order_by('published','desc')
            	               ->limit($pagination->items_per_page)
            	               ->offset($pagination->offset)
            	               ->find_all();
				}
				else
				{
				  	$ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
	    					   ->where('id_category', '=', $categ)
	    					   ->order_by('published','desc')
            	               ->limit($pagination->items_per_page)
            	               ->offset($pagination->offset)
            	               ->find_all();	
				}
    	  
            }
            else
            {
    	 	$ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
    							->order_by('published','desc')
		        	            ->limit($pagination->items_per_page)
		        	            ->offset($pagination->offset)
		        	            ->find_all();
            }
		}
		else
		{
			// array of categories sorted for view
		return array('ads'			=> NULL,
					 'pagination'	=> NULL, 
					 'user'			=> NULL, 
					 'thumb' 		=> NULL,
					 'cat'			=> NULL,
					 'loc'			=> NULL,);
		}

		// user recognition 
		$user = (Auth::instance()->get_user() == NULL) ? NULL : Auth::instance()->get_user() ;
		
		// return image path to display in view 
		$thumb = array();
		foreach ($ads as $a) 
		{

			if(!is_dir($a->gen_img_path($a->id_ad, $a->created)))
			{
				$a->has_images = 0;
				try 
				{
					$a->save();
				} catch (Exception $e) {
					echo $e;
				}
			}
			
			// search and create path of ads, fill array $thumb
			if(is_array($path = $this->image_path($a)))
			{
				foreach ($path as $key => $value) 
				{
					// hash tag to distinguish thumb from big image
					$hashtag = (core::config("theme_default.listing_images") != FALSE) ? strstr($value, 'thumb') : !strstr($value, 'thumb') ;

					if( $hashtag && strstr($value, '_1'))
					{
						$thumb[$a->seotitle] = $value;
					}
					else if (strstr($value, 'thumb') && !array_key_exists($a->seotitle, $thumb))
					{
						$thumb[$a->seotitle] = $value;	
					}
				}
				// case when there are no images , sanity check
				if(!isset($thumb[$a->seotitle]))
				{
					$thumb[$a->seotitle] = NULL;
				}
			}
			else
			{
				$path = NULL;
				$thumb[$a->seotitle] = $path;	
			} 

		}
		
		// array of categories sorted for view
		return array('ads'			=> $ads,
					 'pagination'	=> $pagination, 
					 'user'			=> $user, 
					 'thumb' 		=> $thumb,
					 'cat'			=> $categ,
					 'loc'			=> $locat,);
	}

	// public function search()
	// {
	// 	$search_term = $this->request->query('search');
            
 //            $this->template->bind('content', $content);
	// 		$this->template->content = View::factory('pages/ad/listing');

	// 		$ads = $advert->where('status', '=', Model_Ad::STATUS_PUBLISHED);
			
	//         //search term
	//         if ( strlen($search_term)>2 )
	//         {
	//             $ads->where('title', 'like', '%'.$search_term.'%');
	//         }

	//         $res_count = count($ads);

	//         $pagination = Pagination::factory(array(
	//                     'view'           	=> 'pagination',
	//                     'total_items'      	=> $res_count,
	//                     'items_per_page' 	=> core::config('general.advertisements_per_page'),
	//         ))->route_params(array(
	//                     'controller' 		=> $this->request->controller(),
	//                     'action'     	 	=> $this->request->action(),
	//         ));

	//         if ($res_count>0)
	//         {
	//         	$content->ads = $ads->order_by('created','desc')
	//                                 ->limit($pagination->items_per_page)
	//                                 ->offset($pagination->offset)
	//                                 ->find_all();
	                                   	            
	//         } else $content->ads = NULL;
	       	

	// 		$content->pagination = $pagination->render();
	// 		$content->cat = $cat;
	// 		$content->loc = $loc;
	// 		$content->thumb = NULL;
	// 		$content->user = $user;
	// 		$content->img_path = NULL;
			
        
        
	// }
	
	public function action_sort_category()
	{
		$category_name = $this->request->param('category');

		$sql_cat = new Model_Category();
		$id_cat = $sql_cat->where('name', '=', $category_name)->limit(1)->find();
		
		if($id_cat->loaded())
		{
			$data = $this->list_logic($id_cat->id_category, $location = NULL);

			$this->template->bind('content', $content);
			$this->template->content = View::factory('pages/ad/listing',$data);
			$search_term = $this->request->param('search',NULL);
		}
		else
		{
			Alert::set(Alert::ERROR, __('Category '.'"'.$category_name.'"'.' was not found. Please try again!'));
			$this->request->redirect(Route::url('default', array('controller'=>'ad', 'action'=>'all')));
		}
		
	}

	/**
	 * Advanced search by title, category or location
	 * @param  [string] $advert [Advertisement title]
	 * @param  [string] $cat    [Category name]
	 * @param  [string] $loc    [Location name]
	 * @return [view]         
	 */
	public function action_advansed_search($advert = NULL, $cat = NULL, $loc = NULL)
	{	
		$res = new Model_Ad();
		$res->where('status', '=', Model_Ad::STATUS_PUBLISHED);

		if($advert !== NULL)
		{
			$res->where('title', 'like', '%'.$advert.'%');
		}

		if($cat !== NULL)
		{
			$slug_cat = new Model_Category();
			$slug_cat = $slug_cat->where('name', 'like', '%'.$cat.'%')
								 ->limit(1)
								 ->find();

			$res->where('id_category', '=', $slug_cat->id_category);
			
		}
		if($loc !== NULL)
		{
			$slug_loc = new Model_Location();
			$slug_loc = $slug_loc->where('name', 'like', '%'.$loc.'%')
								 ->limit(1)
								 ->find();
			
			$res->where('id_location', '=', $slug_loc->id_location);
		}
		return $res;

	}


	/**
	 * 
	 * Display single advert. 
	 * @throws HTTP_Exception_404
	 * 
	 */
	public function action_view()
	{
		$this->template->styles 			 = array('css/bootstrap-image-gallery.css' => 'screen',);
		$this->template->scripts['footer'][] = 'js/load-image.min.js';
		$this->template->scripts['footer'][] = 'js/bootstrap-image-gallery.min.js';


		$seotitle = $this->request->param('seotitle',NULL);
		$category = $this->request->param('category');
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
				 ->limit(1)->find();
			
			$cat = ORM::factory('category', $ad->id_category);
			//$loc = ORM::factory('location', $ad->id_location);

			if ($ad->loaded())
			{
                $this->template->title = $ad->title. ' - '.$cat->name;
                $this->template->meta_description = text::removebbcode($ad->description);

				$permission = TRUE; //permission to access advert. 
				if(!Auth::instance()->logged_in() || Auth::instance()->get_user()->id_role != 10)
				{
					$do_hit = $ad->count_ad_hit($ad->id_ad, $ad->id_user); // hits counter
					$permission = FALSE;
				}
				//count how many matches are found 
		        $hits = new Model_Visit();
		        $hits = $hits->where('id_ad','=', $ad->id_ad)->count_all();

		        // show image path if they exist
		    	if($ad->has_images == 1)
				{
					$path = $this->image_path($ad); 
				}else $path = NULL;

				$captcha_show = core::config('advertisement.captcha');	

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/ad/single',array('ad'				=>$ad,
																				   'permission'		=>$permission, 
																				   'hits'			=>$hits, 
																				   'path'			=>$path,
																				   'captcha_show'	=>$captcha_show));

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
	 * Edit advertisement: Update
	 *
	 * All post fields are validated
	 */
	public function action_update()
	{
		//template header
		$this->template->title           	= __('Edit advertisement');
		$this->template->meta_description	= __('Edit advertisement');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen' , 'css/chosen.css' => 'screen');
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= '/js/chosen.jquery.min.js';
		$this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';


		$form = ORM::factory('ad', $this->request->param('id'));
		
		$cat = new Model_Category();
		$cat = $cat->find_all();
		
		$loc = $location = new Model_Location();
		$loc = $loc->find_all();

	
		if(Auth::instance()->logged_in() && Auth::instance()->get_user()->id_user == $form->id_user 
			|| Auth::instance()->logged_in() && Auth::instance()->get_user()->id_role == 10)
		{
			$extra_payment = core::config('payment');
			$count = 0;
			if($form->has_images == 1)
			{
				$current_path = $form->gen_img_path($form->id_ad, $form->created);
				if (is_dir($current_path)){ // sanity check
					$handle = opendir($current_path);
					
					while(FALSE !== ($entry = readdir($handle)))
					{
						if($entry != '.' && $entry != '..') $count++;
					}
					
					$num_images = core::config('advertisement.num_images');
					
					if ($count == 0) 
					{
						$form->has_images = 0;
						try {
							$form->save();
							$img_permission = TRUE;
						} catch (Exception $e) {
							echo "something went wrong";
						}
					}
					elseif($count < $num_images*2) $img_permission = TRUE;
					else
					{
						Alert::set(Alert::INFO, __('You can not upload images anymore limit is: '.$num_images));
						$img_permission = FALSE;
					} 
				}
				else 
				{
					$img_permission = FALSE;
					$form->has_images = 0;
					try {
						$form->save();
						$img_permission = TRUE;
					} catch (Exception $e) {
						echo "something went wrong";
					}
				}
			}else $img_permission = TRUE;
			
			$path = $this->image_path($form);
			$this->template->content = View::factory('pages/ad/edit', array('ad'				=>$form, 
																			  'location'		=>$loc, 
																			  'category'		=>$cat,
																			  'path'			=>$path,
																			  'perm'			=>$img_permission,
																			  'extra_payment'	=>$extra_payment));
		
			if ($this->request->post())
			{

				// deleting single image by path 
				$deleted_image = $this->request->post('img_delete');
				if($deleted_image)
				{
					//$this->request->redirect(Route::url('default', array('controller'=>'ad', 'action'=>'img_delete', 'id'=>$this->request->param('id'))));

					$img_path = $form->gen_img_path($form->id_ad, $form->created);

					
					if (!is_dir($img_path)) 
					{
						return FALSE;
					}
					else
					{	
					// d($img_path.$deleted_image.'.jpg');
						//delete formated image
						unlink($img_path.$deleted_image.'.jpg');

						//delete original image
						$orig_img = str_replace('thumb_', '', $deleted_image);
						unlink($img_path.$orig_img.".jpg");

						$this->request->redirect(Route::url('default', array('controller'=>'ad',
																			'action'=>'update',
																			'id'=>$form->id_ad)));
					}
				}// end of img delete

				$data = array(	'_auth' 		=> $auth 		= 	Auth::instance(),
								'title' 		=> $title 		= 	$this->request->post('title'),
								'seotitle' 		=> $seotitle 	= 	$this->request->post('title'),
								'cat'			=> $cat 		= 	$this->request->post('category'),
								'loc'			=> $loc 		= 	$this->request->post('location'),
								'description'	=> $description = 	$this->request->post('description'),
								'price'			=> $price 		= 	$this->request->post('price'),
								'status'		=> $status		= 	$this->request->post('status'),
								'address'		=> $address 	= 	$this->request->post('address'),
								'website'		=> $website 	= 	$this->request->post('website'),
								'phone'			=> $phone 		= 	$this->request->post('phone'),
								'has_images'	=> 0,
								'user'			=> $user 		= new Model_User()
								); 

				//insert data
				if ($this->request->post('title') != $form->title)
				{
					if($form->has_images == 1)
					{
						$current_path = $form->gen_img_path($form->id_ad, $form->created);
						// rename current image path to match new seoname
						rename($current_path, $form->gen_img_path($form->id_ad, $form->created)); 

					}
					$seotitle = $form->gen_seo_title($data['title']);
					$form->seotitle = $seotitle;
					
				}else $form->seotitle = $form->seotitle;
				 
				$form->title 			= $data['title'];
				$form->id_location 		= $data['loc'];
				$form->id_category 		= $data['cat'];
				$form->description 		= $data['description'];
				$form->status 			= $data['status'];	
				$form->price 			= $data['price']; 								
				$form->address 			= $data['address'];
				$form->website 			= $data['website'];
				$form->phone			= $data['phone']; 

				$obj_img = new Model_Ad();

				// image upload
				$error_message = NULL;
	    		$filename = NULL;

    			if (isset($_FILES['image0']) && $count/2 <= 3)
        		{
	        		$img_files = array($_FILES['image0']);
	            	$filename = $obj_img->save_image($img_files, $form->id_ad, $form->created, $form->seotitle);
        		}
        		if ( $filename == TRUE)
	       		{
		        	$form->has_images = 1;
	        	}

	        	try {
	        		$form->save();
	        		Alert::set(Alert::SUCCESS, __('Advertisement is updated'));

	        		// PAYMENT METHOD ACTIVE
					$moderation = core::config('general.moderation');
					$payment_order = new Model_Order();
					$advert_order = $payment_order->where('id_ad', '=', $this->request->param('id'))
												  ->and_where('status', '=', 0)
												  ->and_where('pay_date', '=', NULL)
												  ->limit(1)->find();

					if(!$advert_order->loaded())
					{
						if($moderation == 2 || $moderation == 3)
						{
							$order_id = $payment_order->make_new_order($data, Auth::instance()->get_user()->id_user, $form->seotitle);

							if($order_id == NULL)
							{
								$this->request->redirect(Route::url('default'));
							}
							// redirect to payment
        					$this->request->redirect(Route::url('payment', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $order_id))); // @TODO - check route
						}
					}
						

	        		$this->request->redirect(Route::url('default', array('controller'=>'ad',
																			'action'=>'update',
																			'id'=>$form->id_ad)));
	        	} catch (Exception $e) {
	        		Alert::set(Alert::ALERT, __('Something went wrong with uploading pictures'));
	        	}
    		
			}
		}
		else
		{
			Alert::set(Alert::ERROR, __('You dont have permission to access this link'));
			$this->request->redirect(Route::url('default'));
		}
	}

	/**
	 * [image_path Get directory path of specific advert.]
	 * @param  [array] $data [all values of one advert.]
	 * @return [array]       [array of dir. path where images of advert. are ]
	 */
	public function image_path($data)
	{
		$obj_ad = new Model_Ad();
		$directory = $obj_ad->gen_img_path($data->id_ad, $data->created);

		$path = array();
		if(is_dir($directory))
		{	
			$filename = array_diff(scandir($directory, 1), array('..','.')); //return all file names , and store in array 

			foreach ($filename as $filename) {
				array_push($path, $directory.$filename);		
			}
		}
		else
		{ 	
			return FALSE ;
		}

		return $path;
	}

	/**
	 * [action_to_top] [pay to go on top, and make order]
	 *
	 * @TODO if paymant is corrent and done update order table(status, pay_date), and put it to top (change published date)
	 */
	public function action_to_top()
	{
		$payer_id 		= Auth::instance()->get_user()->id_user; 
		$id_product 	= Paypal::to_top;
		$description 	= 'to_top';
		// update orders table
		// fields
		$ad = new Model_Ad($this->request->param('id'));
	

		
		$ord_data = array('id_user' 	=> $payer_id,
						  'id_ad' 		=> $ad->id_ad,
						  'id_product' 	=> $id_product,
						  'paymethod' 	=> 'paypal', // @TODO - to strict
						  'currency' 	=> core::config('payment.paypal_currency'),
						  'amount' 		=> core::config('payment.pay_to_go_on_top'),
						  'description'	=> $description);

		$order_id = new Model_Order(); // create order , and returns order id
		$order_id = $order_id->set_new_order($ord_data);
	
		
		// redirect to payment
		$this->request->redirect(Route::url('default', array('controller' =>'payment_paypal','action'=>'form' ,'id' => $order_id)));

	}
	
	/**
	 * [action_to_featured] [pay to go in featured]
	 *
	 * @TODO - when paypal returns token, update
	 */
	public function action_to_featured()
	{
		$payer_id 		= Auth::instance()->get_user()->id_user; 
		$id_product 	= Paypal::to_featured;
		$description 	= 'to_featured';

		// update orders table
		// fields
		$ad = new Model_Ad($this->request->param('id'));
	

		$ord_data = array('id_user' 	=> $payer_id,
						  'id_ad' 		=> $ad->id_ad,
						  'id_product' 	=> $id_product,
						  'paymethod' 	=> 'paypal', // @TODO - to strict
						  'currency' 	=> core::config('payment.paypal_currency'),
						  'amount' 		=> core::config('payment.pay_to_go_on_feature'),
						  'description'	=> $description);
		
		$order_id = new Model_Order(); // create order , and returns order id
		$order_id = $order_id->set_new_order($ord_data);
		// redirect to payment
		$this->request->redirect(Route::url('default', array('controller' =>'payment_paypal','action'=>'form' ,'id' => $order_id)));
	}
	
	public function action_confirm_post()
	{
		$advert_id = $this->request->param('id');

		$advert = new Model_Ad($advert_id);

		if($advert->loaded())
		{
			if(core::config('general.moderation') == 3)
			{

				$advert->status = 1; // status active
				$advert->published = Date::unix2mysql(time());

				try 
				{
					$advert->save();
					Alert::set(Alert::INFO, __('Your advertisement is successfully activated! Thank you!'));
					$this->request->redirect(Route::url('ad', array('category'=>$advert->id_category, 'seotitle'=>$advert->seotitle)));	
				} 
				catch (Exception $e) 
				{
					throw new HTTP_Exception_500($e->getMessage());
				}
			}
		}
	}

	
}// End ad controller

