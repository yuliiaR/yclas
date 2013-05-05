<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Profile extends Auth_Controller {

    

	public function action_index()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home')));
		
		$this->template->title = __('Home');
		//$this->template->scripts['footer'][] = 'js/user/index.js';
		$this->template->content = View::factory('oc-panel/home-user');
	}


	public function action_changepass()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Change password')));
		
		$this->template->title   = __('Change password');

		$user = Auth::instance()->get_user();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('oc-panel/profile/edit',array('user'=>$user));
		$this->template->content->msg ='';

		if ($this->request->post() AND CSRF::valid())
		{
			$user = Auth::instance()->get_user();
			if(Auth::instance()->hash(core::post('password_old')) == $user->password )
			{
				if ($this->request->post('password1')==$this->request->post('password2'))
				{
					$new_pass = $this->request->post('password1');
					if(!empty($new_pass)){

						$user->password = $this->request->post('password1');

						try
						{
							$user->save();
						}
						catch (ORM_Validation_Exception $e)
						{
							throw new HTTP_Exception_500($e->getMessage());
						}
						catch (Exception $e)
						{
							throw new HTTP_Exception_500($e->getMessage());
						}
						
						
						Alert::set(Alert::SUCCESS, __('Password is changed'));
					}
					else
					{
						Form::set_errors(array(__('Nothing is provided')));
					}
				}
				else
				{
					Form::set_errors(array(__('Passwords do not match')));
				}
			}
		}

	  
	}

	public function action_edit()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit profile')));
		// $this->template->title = $user->name;
		//$this->template->meta_description = $user->name;//@todo phpseo
		$user = Auth::instance()->get_user();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('oc-panel/profile/edit',array('user'=>$user));
		// $this->template->content = View::factory('pages/useredit',array('user'=>$user, 'captcha_show'=>$captcha_show));

		if($this->request->post())
		{
			
			$user->name = $this->request->post('name');
			$user->email = $this->request->post('email');
			$user->seoname = URL::title($this->request->post('name'), '-', FALSE);
			// $user->password2 = $this->request->post('password2');
			
			$password1 = $this->request->post('password1');
			if(!empty($password1))
			{
				if($this->request->post('password1') == $this->request->post('password2'))
				{
					$user->password = $this->request->post('password1');
				}
				else
				{
					Alert::set(Alert::ERROR, __('New password is invalid, or they do not match! Please try again.'));
					$this->request->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
				}
			} 

			try {
				$user->save();
				Alert::set(Alert::SUCCESS, __('You have successfuly chaged your data'));
				$this->request->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
				
			} catch (Exception $e) {
				//throw 500
				throw new HTTP_Exception_500();
			}	
		}
	}

	public function action_ads()
	{
		$cat = new Model_Category();
		$list_cat = $cat->find_all(); // get all to print at sidebar view
		
		$loc = new Model_Location();
		$list_loc = $loc->find_all(); // get all to print at sidebar view

		$user = Auth::instance()->get_user();
		$ads = new Model_Ad();

		$my_adverts = $ads->where('id_user', '=', $user->id_user);

		$res_count = $my_adverts->count_all();
		
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('general.advertisements_per_page')
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                 
    	    ));

    	    Breadcrumbs::add(Breadcrumb::factory()->set_title(__("My Advertisement page ").$pagination->current_page));
    	    $ads = $my_adverts->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();


          	$this->template->content = View::factory('oc-panel/profile/ads', array('ads'=>$ads,
          																		   'pagination'=>$pagination,
          																		   'category'=>$list_cat,
          																		   'location'=>$list_loc,
          																		   'user'=>$user));
        }
        else
        {

        	$this->template->content = View::factory('oc-panel/profile/ads', array('ads'=>$ads,
          																		   'pagination'=>NULL,
          																		   'category'=>NULL,
          																		   'location'=>NULL,
          																		   'user'=>$user));
        }
	}

	/**
	 * Mark advertisement as deactivated : STATUS = 50
	 */
	public function action_deactivate()
	{

		$id = $this->request->param('id');
		
		
		if (isset($id))
		{

			$deact_ad = new Model_Ad($id);

			if ($deact_ad->loaded())
			{
				if ($deact_ad->status != 50)
				{
					$deact_ad->status = 50;
					
					try
					{
						$deact_ad->save();
					}
						catch (Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
					}
				}
				else
				{				
					Alert::set(Alert::ALERT, __("Warning, advertisemet is already marked as 'deactivated'"));
					Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
				} 
			}
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
		}
		
		Alert::set(Alert::SUCCESS, __('Success, advertisemet is deactivated'));
		Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
	}

	/**
	 * Mark advertisement as active : STATUS = 1
	 */
	
	public function action_activate()
	{

		$id = $this->request->param('id');
		
		if (isset($id))
		{
			$active_ad = new Model_Ad($id);

			if ($active_ad->loaded())
			{
				if ($active_ad->status != 1)
				{
					$active_ad->published = Date::unix2mysql(time());
					$active_ad->status = 1;
					
					try
					{
						$active_ad->save();
					}
						catch (Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
					}
				}
				else
				{				
					Alert::set(Alert::ALERT, __("Warning, advertisemet is already marked as 'active'"));
					Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
				} 
			}
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
		}
		

		// send confirmation email
		$cat = new Model_Category($active_ad->id_category);
		$usr = new Model_User($active_ad->id_user);
		if($usr->loaded())
		{
			//we get the QL, and force the regen of token for security
			$url_ql = $usr->ql('ad',array( 'category' => $cat->seoname, 
		 	                                'seotitle'=> $active_ad->seotitle),TRUE);

			$ret = $usr->email('ads.activated',array("[USER.OWNER]"=>$usr->name, "[AD.ID]"=>$active_ad->id_ad));	
		}	

		if (Core::config('sitemap.on_post') == TRUE)
			Sitemap::generate();

		Alert::set(Alert::SUCCESS, __('Success, advertisemet is active and published'));
		Request::current()->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'ads')));
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
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		 	

		$form = new Model_Ad($this->request->param('id'));
		
		$cat = new Model_Category();
		$cat_list = $cat->find_all();
		
		$loc = $location = new Model_Location();
		$loc = $loc->find_all();

	
		if(Auth::instance()->logged_in() && Auth::instance()->get_user()->id_user == $form->id_user 
			|| Auth::instance()->logged_in() && Auth::instance()->get_user()->id_role == 10)
		{
			$extra_payment = core::config('payment');
			$count = 0;
			if($form->has_images == 1)
			{
				//get path of image
				$current_path = $form->gen_img_path($form->id_ad, $form->created);
				if (is_dir($current_path)){ // sanity check
					$handle = opendir($current_path);
					
					// ignore . and .. in folder
					while(FALSE !== ($entry = readdir($handle)))
					{
						if($entry != '.' && $entry != '..') $count++;
					}
					
					$num_images = core::config('advertisement.num_images'); // get limit 
					
					// count them and if 0 set has_images to 0, send param img_permission to alow uploading more
					if ($count == 0) 
					{
						$form->has_images = 0;
						try {
							$form->save();
							$img_permission = TRUE;
						} catch (Exception $e) {
							//throw 500
 			 				throw new HTTP_Exception_500($e->getMessage());
						}
					}
					elseif($count < $num_images*2) $img_permission = TRUE;
					else
						$img_permission = FALSE; // doesnt allow uploading more images 
				}
				else // case when we have images 
				{
					$img_permission = FALSE;
					$form->has_images = 0;
					try {
						$form->save();
						$img_permission = TRUE;
					} catch (Exception $e) {
						//throw 500
 			 			throw new HTTP_Exception_500($e->getMessage());
					}
				}
			}else $img_permission = TRUE;
			
			Breadcrumbs::add(Breadcrumb::factory()->set_title("Update"));
			$instance_ad = new Controller_Ad($this->request, $this->response);  
			$path = $instance_ad->image_path($form);
			$this->template->content = View::factory('oc-panel/profile/edit_ad', array('ad'				=>$form, 
																					   'location'		=>$loc, 
																					   'category'		=>$cat_list,
																					   'path'			=>$path,
																					   'perm'			=>$img_permission,
																					   'extra_payment'	=>$extra_payment));
		
			if ($this->request->post())
			{

				// deleting single image by path 
				$deleted_image = $this->request->post('img_delete');
				if($deleted_image)
				{
					$img_path = $form->gen_img_path($form->id_ad, $form->created);
					
					if (!is_dir($img_path)) 
					{
						return FALSE;
					}
					else
					{	
					
						//delete formated image
						unlink($img_path.$deleted_image.'.jpg');

						//delete original image
						$orig_img = str_replace('thumb_', '', $deleted_image);
						unlink($img_path.$orig_img.".jpg");

						$this->request->redirect(Route::url('oc-panel', array('controller'	=>'profile',
																			  'action'		=>'update',
																			  'id'			=>$form->id_ad)));
					}
				}// end of img delete

				$data = array(	'_auth' 		=> $auth 		= 	Auth::instance(),
								'title' 		=> $title 		= 	$this->request->post('title'),
								'seotitle' 		=> $seotitle 	= 	$this->request->post('title'),
								'cat'			=> $category 	= 	$this->request->post('category'),
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
					
				}
				else 
					$form->seotitle = $form->seotitle;
				 
				$form->title 			= $data['title'];
				$form->id_location 		= $data['loc'];
				$form->id_category 		= $data['cat'];
				$form->description 		= $data['description'];
				$form->status 			= $data['status'];	
				$form->price 			= $data['price']; 								
				$form->address 			= $data['address'];
				$form->website 			= $data['website'];
				$form->phone			= $data['phone']; 

				$obj_ad = new Model_Ad();

				// image upload
				$error_message = NULL;
	    		$filename = NULL;

    			if (isset($_FILES['image0']) && $count/2 <= 3)
        		{
	        		$img_files = array($_FILES['image0']);
	            	$filename = $obj_ad->save_image($img_files, $form->id_ad, $form->created, $form->seotitle);
        		}
        		if ( $filename == TRUE)
	       		{
		        	$form->has_images = 1;
	        	}

	        	try 
	        	{
	        		
	        		// if user changes category, do payment first
	        		// moderation 2 -> payment on, moderation 5 -> payment with moderation
	        		// data['cat'] -> category selected , last_known_ad->id_category -> obj of current ad (before save) 
	        		$moderation = core::config('general.moderation');
	        		$last_known_ad = $obj_ad->where('id_ad', '=', $this->request->param('id'))->limit(1)->find();
	        		if($moderation == 2 || $moderation == 5)
	        		{
	        			// PAYMENT METHOD ACTIVE
						$payment_order = new Model_Order();
						$advert_have_order = $payment_order->where('id_ad', '=', $this->request->param('id'));
						   
	        			if($data['cat'] == $last_known_ad->id_category) // user didn't changed category 
	        			{
	        				// check if he payed when ad was created (is successful), 
	        				// if not give him alert that he didn't payed, and ad will not be published until he do  
							$cat_check = $cat->where('id_category', '=', $last_known_ad->id_category)->limit(1)->find(); // current category
							$advert_have_order->and_where('description', '=', $cat_check->seoname)->limit(1)->find();
							if($advert_have_order->loaded()) // if user have order
							{

								if($advert_have_order->status != Model_Order::STATUS_PAID)
								{ // order is not payed,  
									$form->status = 0;
									Alert::set(Alert::INFO, __('Advertisement is updated, but it won\'t be published until payment is done.'));
								}
								else // order is payed, update status and publish 
								{
									if($moderation == 2)
									{
										$form->status = 1;
										Alert::set(Alert::SUCCESS, __('Advertisement is updated!'));	
									}
									else if($moderation == 5)
										Alert::set(Alert::SUCCESS, __('Advertisement is updated!'));
								}
							}
							$form->save();
	        				$this->request->redirect(Route::url('oc-panel', array('controller'	=>'profile',
																				  'action'		=>'update',
																				  'id'			=>$form->id_ad)));
						
	        			} // end - same category
	        			else // different category
	        			{ 
	        				// user have pending order with new category(possible that he previously tried to do the same action)
	        				
							$cat_check = $cat->where('id_category', '=', $data['cat'])->limit(1)->find(); // newly selected category
							$advert_have_order->and_where('description', '=', $cat_check->seoname)->limit(1)->find();
	        				if($advert_have_order->loaded())
	        				{
	        					// sanity check -> we don't want to charge him twice for same category 
	        					if($advert_have_order->status != Model_Order::STATUS_PAID)
	        						$this->request->redirect(Route::url('default', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $advert_have_order->id_order))); 	
								else // order is payed, update status and publish 
								{
									if($moderation == 2)
									{
										$form->status = 1;
										Alert::set(Alert::SUCCESS, __('Advertisement is updated!'));	
									}
									else if($moderation == 5)
										Alert::set(Alert::SUCCESS, __('Advertisement is updated!'));
									
								}
	        				}
	        				else // user doesn't have order -> create new order and redirect him to payment (do not update status until payment is confirmed)
	        				{
	        					$order_id = $payment_order->make_new_order($data, Auth::instance()->get_user()->id_user, $form->seotitle);
	        					
	        					if($order_id == NULL) // this is the case when in make_new_order we detect that category OR category_parent doesn't have price
								{
									if($moderation == 2) // publish
										$form->status = 1;
								}
								else
								{
									// redirect to payment
				        			$this->request->redirect(Route::url('default', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $order_id))); // @TODO - check route	
								}								
	        				}	
	        			}
	        		}
	        		
	        		// save ad  
	        		$form->save();
	        		Alert::set(Alert::SUCCESS, __('Advertisement is updated'));

	        		$this->request->redirect(Route::url('oc-panel', array('controller'	=>'profile',
																		  'action'		=>'update',
																		  'id'			=>$form->id_ad)));
	        	} catch (Exception $e) {
	 				//throw 500
					throw new HTTP_Exception_500($e->getMessage());       		
	        	}

	        	
			}
		}
		else
		{
			Alert::set(Alert::ERROR, __('You dont have permission to access this link'));
			$this->request->redirect(Route::url('default'));
		}
	}

	public function action_stats()
   	{
    
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats')));

        $this->template->styles = array('css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('js/bootstrap-datepicker.js',
                                                    'js/oc-panel/stats/dashboard.js');
        
        $this->template->title = __('Stats');
        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/profile/stats');

        //Getting the dates and range
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);

        //mysql formated dates
        $my_from_date = Date::unix2mysql($from_date);
        $my_to_date   = Date::unix2mysql($to_date);

        //dates range we are filtering
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
        
        //dates displayed in the form
        $content->from_date = date('Y-m-d',$from_date);
        $content->to_date   = date('Y-m-d',$to_date) ;


        /////////////////////VISITS STATS////////////////////////////////

        $user = Auth::instance()->get_user();
        $ads = new Model_Ad();
        $collection_of_user_ads = $ads->where('id_user', '=', $user->id_user)->find_all();

        $list_ad = array();
        foreach ($collection_of_user_ads as $key) {
        	//TODO make a list of ads (array), and than pass this array to query (IN).. To get correct visits
        	$list_ad[] = $key->id_ad;
        }
        
        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $visits = $query->as_array('date');

        $visits_daily = array();
        foreach ($dates as $date) 
        {
            $count = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;
            $visits_daily[] = array('date'=>$date['date'],'count'=> $count);
        } 

        $content->visits_daily =  $visits_daily;


        //Today and Yesterday Views
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array(date('Y-m-d',strtotime('-1 day')),date::unix2mysql()))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_yesterday = (isset($visits[0]['count']))?$visits[0]['count']:0;
        $content->visits_today     = (isset($visits[1]['count']))?$visits[1]['count']:0;


        //Last 30 days visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $visits = $query->as_array();
        $content->visits_month = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //total visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->where('id_ad', 'in', $list_ad)
                        ->from('visits')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_total = (isset($visits[0]['count']))?$visits[0]['count']:0;
        
   }



}
