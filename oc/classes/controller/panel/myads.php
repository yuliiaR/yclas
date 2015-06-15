<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Myads extends Auth_Frontcontroller {

    
	public function action_index()
	{
		$cat = new Model_Category();
		$list_cat = $cat->find_all(); // get all to print at sidebar view
		
		$loc = new Model_Location();
		$list_loc = $loc->find_all(); // get all to print at sidebar view

		$user = Auth::instance()->get_user();
		$ads = new Model_Ad();

		Controller::$full_width = TRUE;

		$my_adverts = $ads->where('id_user', '=', $user->id_user);

		$res_count = $my_adverts->count_all();
		
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'oc-panel/crud/pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('advertisement.advertisements_per_page')
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                 
    	    ));

    	    Breadcrumbs::add(Breadcrumb::factory()->set_title(__("My Advertisement page ").$pagination->current_page));
    	    $ads = $my_adverts->order_by('published','desc')
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
				if(Auth::instance()->get_user()->id_user != $deact_ad->id_user AND 
				   Auth::instance()->get_user()->id_role != Model_Role::ROLE_ADMIN)

                {
                    Alert::set(Alert::ALERT, __("This is not your advertisement."));
                    HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'ads')));
                }
                elseif ($deact_ad->status != Model_Ad::STATUS_UNAVAILABLE)
				{
					$deact_ad->status = Model_Ad::STATUS_UNAVAILABLE;
					
					try
					{
						$deact_ad->save();
					}
						catch (Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}
				}
				else
				{				
					Alert::set(Alert::ALERT, __("Warning, Advertisement is already marked as 'deactivated'"));
					HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'ads')));
				} 
			}
			else
			{
				//throw 404
				throw HTTP_Exception::factory(404,__('Page not found'));
			}
		}
		
		Alert::set(Alert::SUCCESS, __('Advertisement is deactivated'));
		HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'ads')));
	}

	/**
	 * Mark advertisement as active : STATUS = 1
	 */
	
	public function action_activate()
	{
        $user = Auth::instance()->get_user();
		$id = $this->request->param('id');
		
		if (isset($id))
		{
			$active_ad = new Model_Ad($id);

			if ($active_ad->loaded())
			{
                $activate = FALSE;

                //admin whatever he wants
                if ($user->id_role == Model_Role::ROLE_ADMIN )
                {
                    $activate = TRUE;
                }
                //chekc user owner and if not moderation
                elseif ($user->id_user == $active_ad->id_user AND !in_array(core::config('general.moderation'), Model_Ad::$moderation_status) )
                {
                    $activate = TRUE;
                }
                else
                {
                    Alert::set(Alert::ALERT, __("This is not your advertisement."));
                }

                //its not published
                if ($active_ad->status == Model_Ad::STATUS_PUBLISHED)
                {
                    $activate = FALSE;
                    Alert::set(Alert::ALERT, __("Advertisement is already marked as 'active'"));
                }


                //activate the ad
                if ($activate === TRUE)
                {
                    $active_ad->published  = Date::unix2mysql(time());
                    $active_ad->status     = Model_Ad::STATUS_PUBLISHED;
                    
                    try
                    {
                        $active_ad->save();
                    }
                    catch (Exception $e)
                    {
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                }
                //we dont activate something happened
                else
                {
                    HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'ads')));
                }

			}
			else
			{
				//throw 404
				throw HTTP_Exception::factory(404,__('Page not found'));
			}
		}
		

		// send confirmation email
		$cat = new Model_Category($active_ad->id_category);
		$usr = new Model_User($active_ad->id_user);
		if($usr->loaded())
		{
            $edit_url   = Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$active_ad->id_ad));
            $delete_url = Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$active_ad->id_ad));

			//we get the QL, and force the regen of token for security
			$url_ql = $usr->ql('ad',array( 'category' => $cat->seoname, 
		 	                                'seotitle'=> $active_ad->seotitle),TRUE);

			$ret = $usr->email('ads-activated',array('[USER.OWNER]'=>$usr->name,
													 '[URL.QL]'=>$url_ql,
													 '[AD.NAME]'=>$active_ad->title,
													 '[URL.EDITAD]'=>$edit_url,
                    								 '[URL.DELETEAD]'=>$delete_url));	
		}	

		Alert::set(Alert::SUCCESS, __('Advertisement is active and published'));
		HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'ads')));
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
		
		Controller::$full_width = TRUE;
		
		//local files
        if (Theme::get('cdn_files') == FALSE)
        {
            $this->template->styles = array('css/jquery.sceditor.default.min.css' => 'screen');
            
            $this->template->scripts['footer'] = array( 'js/jquery.sceditor.min.js',
                                                        'js/jquery.sceditor.bbcode.min.js',
                                                        'js/jquery.chained.min.js',
                                                        '//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7',
                                                        '//cdn.jsdelivr.net/gmaps/0.4.15/gmaps.min.js',
                                                        'js/oc-panel/edit_ad.js');
        }
        else
        {
            $this->template->styles = array('css/jquery.sceditor.default.min.css' => 'screen');
            
            $this->template->scripts['footer'] = array( 'js/jquery.sceditor.min.js',
                                                        'js/jquery.sceditor.bbcode.min.js',
                                                        'js/jquery.chained.min.js',
                                                        '//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7',
                                                        '//cdn.jsdelivr.net/gmaps/0.4.15/gmaps.min.js',
                                                        'js/oc-panel/edit_ad.js');
        }



		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		 	

		$form = new Model_Ad($this->request->param('id'));
		
	
		if(Auth::instance()->get_user()->id_user == $form->id_user 
            OR Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN
            OR Auth::instance()->get_user()->id_role == Model_Role::ROLE_MODERATOR)
		{
            $original_category = $form->category;

			$extra_payment = core::config('payment');

            $cat = new Model_Category();
            $loc = new Model_Location();
			
            //find all, for populating form select fields 
            $categories         = Model_Category::get_as_array();  
            $order_categories   = Model_Category::get_multidimensional();
            $parent_category    = Model_Category::get_by_deep();

            //get locations
            $locations         = Model_Location::get_as_array();  
            $order_locations   = Model_Location::get_multidimensional();
            $loc_parent_deep   = Model_Location::get_by_deep();

			
		
			if ($this->request->post())
			{
				
				// deleting single image by path 
				$deleted_image = core::post('img_delete');
				if(is_numeric($deleted_image))
				{
					$img_path = $form->image_path();
					$img_seoname = $form->seotitle;
					
					// delete image from Amazon S3
					if (core::config('image.aws_s3_active'))
					{
						require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
						$s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));
						
						//delete original image
						$s3->deleteObject(core::config('image.aws_s3_bucket'), $img_path.$img_seoname.'_'.$deleted_image.'.jpg');
						//delete formated image
						$s3->deleteObject(core::config('image.aws_s3_bucket'), $img_path.'thumb_'.$img_seoname.'_'.$deleted_image.'.jpg');
						
						//re-ordering image file names
						for($i = $deleted_image; $i < $form->has_images; $i++)
						{
							//rename original image
							$s3->copyObject(core::config('image.aws_s3_bucket'), $img_path.$img_seoname.'_'.($i+1).'.jpg', core::config('image.aws_s3_bucket'), $img_path.$img_seoname.'_'.$i.'.jpg', S3::ACL_PUBLIC_READ);
							$s3->deleteObject(core::config('image.aws_s3_bucket'), $img_path.$img_seoname.'_'.($i+1).'.jpg');
							//rename formated image
							$s3->copyObject(core::config('image.aws_s3_bucket'), $img_path.'thumb_'.$img_seoname.'_'.($i+1).'.jpg', core::config('image.aws_s3_bucket'), $img_path.'thumb_'.$img_seoname.'_'.$i.'.jpg', S3::ACL_PUBLIC_READ);
							$s3->deleteObject(core::config('image.aws_s3_bucket'), $img_path.'thumb_'.$img_seoname.'_'.($i+1).'.jpg');
						}
					}
					
					//delte image from local filesystem
					if (!is_dir($img_path)) 
						return FALSE;
					else
					{	
						//delete original image
						@unlink($img_path.$img_seoname.'_'.$deleted_image.'.jpg');
						//delete formated image
						@unlink($img_path.'thumb_'.$img_seoname.'_'.$deleted_image.'.jpg');
						
						//re-ordering image file names
						for($i = $deleted_image; $i < $form->has_images; $i++)
						{
							rename($img_path.$img_seoname.'_'.($i+1).'.jpg', $img_path.$img_seoname.'_'.$i.'.jpg');
							rename($img_path.'thumb_'.$img_seoname.'_'.($i+1).'.jpg', $img_path.'thumb_'.$img_seoname.'_'.$i.'.jpg');
						}
					}
					
					$form->has_images = ($form->has_images > 0) ? $form->has_images-1 : 0;
					$form->last_modified = Date::unix2mysql();
					try 
					{
						$form->save();
					} 
					catch (Exception $e) 
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}
					
					$this->redirect(Route::url('oc-panel', array('controller'	=>'myads',
																		  'action'		=>'update',
																		  'id'			=>$form->id_ad)));
				}// end of img delete

				$data = array(	'_auth' 		=> $auth 		= 	Auth::instance(),
								'title' 		=> $title 		= 	Text::banned_words(core::post('title')),
								//'seotitle' 		=> $seotitle 	= 	core::post('title'),
								'cat'			=> $category 	= 	core::post('category'),
								'loc'			=> $loc 		= 	core::post('location'),
								'description'	=> $description = 	Text::banned_words(core::post('description')),
								'price'			=> $price 		= 	floatval(str_replace(',', '.', core::post('price'))),
								// 'status'		=> $status		= 	core::post('status'),
								'address'		=> $address 	= 	core::post('address'),
								'website'		=> $website 	= 	core::post('website'),
								'stock'			=> $stock 		= 	core::post('stock'),
								'phone'			=> $phone 		= 	core::post('phone'),
								'has_images'	=> 0,
								'user'			=> $user 		= new Model_User(),
								'latitude'		=> $latitude 	= 	core::post('latitude'),
								'longitude'		=> $longitude 	= 	core::post('longitude')
								); 

				// append to $data new custom values
	            foreach ($_POST as $name => $field) 
	            {
	            	// get by prefix
					if (strpos($name,'cf_') !== false) 
					{
						$data[$name] = $field;
						
						if($field == '0000-00-00' OR $field == "" OR $field == NULL )
							$data[$name] = NULL;

						//checkbox when selected return string 'on' as a value
						if($field == 'on')
						{
							$data[$name] = 1;
						}
					}
	        	}

				//update stock, if non numeric to NULL
				$form->stock = (is_numeric($data['stock']))?$data['stock']:NULL;
				
				$form->title 			= $data['title'];
				$form->id_location 		= $data['loc'];
				$form->id_category 		= $data['cat'];
				$form->description 		= $data['description'];
				$form->price 			= $data['price']; 								
				$form->address 			= $data['address'];
				$form->website 			= $data['website'];
				$form->phone 			= $data['phone'];
				$form->latitude 			= $data['latitude'];
				$form->longitude 		= $data['longitude'];

				// set custom values
				foreach ($data as $key => $value) 
	            {
	            	// get only custom values with prefix
					if (strpos($key,'cf_') !== false) 
					{
						$form->$key = $value;
					}
	        	}
	        	// d($data['cf_radio']);
				$obj_ad = new Model_Ad();

	        	// IMAGE UPLOAD 
				// in case something wrong happens user is redirected to edit advert. 
	    		$filename = NULL;
	    		$counter = 0;

	    		for ($i=0; $i < core::config("advertisement.num_images"); $i++) 
	    		{ 
	    			$counter++;

	    			if (isset($_FILES['image'.$i]))
	        		{
		            	$filename = $form->save_image($_FILES['image'.$i]);
	        		}
	        		
	        		if ($filename){
			        	$form->has_images++;
			        	$form->last_modified = Date::unix2mysql();
			        	try 
						{
							$form->save();
						} 
						catch (Exception $e) 
						{
							throw HTTP_Exception::factory(500,$e->getMessage());
						}
	        		}
		        	
		        	if($filename = FALSE)
		        		$this->redirect(Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$form->id_ad)));
		        }

                // update status on re-stock
                if(is_numeric($data['stock']))
                {
                    //not really sure of this lines...
                    if($form->stock == 0 OR $data['stock'] == 0)
                        $form->status = Model_Ad::STATUS_UNAVAILABLE;
                    elseif($data['stock'] > 0 AND $form->status == Model_Ad::STATUS_UNAVAILABLE)
                        $form->status = Model_Ad::STATUS_PUBLISHED;
                }

	        	$moderation = core::config('general.moderation');

                //payment for category only if category changed
        		if( ($moderation == Model_Ad::PAYMENT_ON OR $moderation == Model_Ad::PAYMENT_MODERATION) AND $data['cat'] !== $original_category->id_category )
        		{
                    $amount = 0;
                    $new_cat = new Model_Category($data['cat']);

                    // check category price, if 0 check parent
                    if($new_cat->price == 0)
                    {
                        $cat_parent = new Model_Category($new_cat->id_category_parent);

                        //category without price
                        if($cat_parent->price == 0)
                        {
                            //swapping moderation since theres no price :(
                            if ($moderation == Model_Ad::PAYMENT_ON)
                                $moderation = Model_Ad::POST_DIRECTLY;
                            elseif($moderation == Model_Ad::PAYMENT_MODERATION)
                                $moderation = Model_Ad::MODERATION_ON;
                        }
                        else
                            $amount = $cat_parent->price;
                    }
                    else
                        $amount = $new_cat->price;
        			
                    //only process apyment if you need to pay
                    if ($amount > 0)
                    {
                        try {
                            //put the ad into moderation since we want payment + moderation
                            if($moderation == Model_Ad::PAYMENT_MODERATION)
                                $form->status = Model_Ad::STATUS_NOPUBLISHED;

                            $form->save();
                        } 
                        catch (Exception $e){
                            throw HTTP_Exception::factory(500,$e->getMessage());
                        }

                        $order = Model_Order::new_order($form, $form->user, Model_Order::PRODUCT_CATEGORY, $amount, NULL, Model_Order::product_desc($id_product).' '.$new_ad->category->name);
                        // redirect to invoice
                        $this->redirect(Route::url('default', array('controller'=> 'ad','action'=>'checkout' , 'id' => $order->id_order)));
                    }

        		}
        		
                // ad edited but we have moderation on, so goes to moderation queue unless you are admin
        		if( ($moderation == Model_Ad::MODERATION_ON 
                    OR $moderation == Model_Ad::EMAIL_MODERATION
                    OR $moderation == Model_Ad::PAYMENT_MODERATION) AND Auth::instance()->get_user()->id_role != Model_Role::ROLE_ADMIN ) 
                {
                    //NOTIFY ADMIN
                    // updated ad notification email to admin (notify_email), if set to TRUE 
                    if(core::config('email.new_ad_notify') == TRUE)
                    {
                        $url_ad = Route::url('ad', array('category'=>$form->category->seoname,'seotitle'=>$form->seotitle));
                        
                        $replace = array('[URL.AD]'        =>$url_ad,
                                         '[AD.TITLE]'      =>$form->title);

                        Email::content(Email::get_notification_emails(),
                                            core::config('general.site_name'),
                                            core::config('email.notify_email'),
                                            core::config('general.site_name'),
                                            'ads-to-admin',
                                            $replace);
                    }
                    
                    Alert::set(Alert::INFO, __('Advertisement is updated, but first administrator needs to validate. Thank you for being patient!'));
                    $form->status = Model_Ad::STATUS_NOPUBLISHED;
                }
                else
                {
                    Alert::set(Alert::SUCCESS, __('Advertisement is updated'));
                }
                    
                // save ad
        		try {
                    $form->save();
                } 
                catch (Exception $e){
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
        		

        		$this->redirect(Route::url('oc-panel', array('controller'	=>'myads', 'action' =>'update', 'id' =>$form->id_ad)));
	        	
			}

            //get all orders
            $orders = new Model_Order();
            $orders = $orders->where('id_user', '=', $form->id_user)
                            ->where('status','=',Model_Order::STATUS_CREATED)
                            ->where('id_ad','=',$form->id_ad)->find_all();

            Breadcrumbs::add(Breadcrumb::factory()->set_title("Update"));
            $this->template->content = View::factory('oc-panel/profile/edit_ad', array('ad'                 =>$form, 
                                                                                       'locations'          =>$locations,
                                                                                       'order_locations'    =>$order_locations, 
                                                                                       'categories'         =>$categories,
                                                                                       'order_categories'   =>$order_categories,
                                                                                       'order_parent_deep'  =>$parent_category,
                                                                                       'loc_parent_deep'    =>$loc_parent_deep,
                                                                                       'extra_payment'      =>$extra_payment,
                                                                                       'orders'             =>$orders,
                                                                                       'fields'             => Model_Field::get_all()));

		}
		else
		{
			Alert::set(Alert::ERROR, __('You dont have permission to access this link'));
			$this->redirect(Route::url('default'));
		}
	}

    /**
     * confirms the post of and advertisement
     * @return void 
     */
    public function action_confirm()
    {
        $advert = new Model_Ad($this->request->param('id'));

        if($advert->loaded())
        {

            if(Auth::instance()->get_user()->id_user !== $advert->id_user)
            {
                Alert::set(Alert::ALERT, __("This is not your advertisement."));
                HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'ads')));
            }

            if(core::config('general.moderation') == Model_Ad::EMAIL_CONFIRMATION)
            {
                $advert->status = Model_Ad::STATUS_PUBLISHED; // status active
                $advert->published = Date::unix2mysql();

                try 
                {
                    $advert->save();

                    //subscription is on
                    $data = array(  'title'         => $advert->title,
                                    'cat'           => $advert->category,
                                    'loc'           => $advert->location,  
                                 );

                    Model_Subscribe::find_subscribers($data, floatval(str_replace(',', '.', $advert->price)), $advert->seotitle); // if subscription is on
                    Alert::set(Alert::INFO, __('Your advertisement is successfully activated! Thank you!'));
                        
                } 
                catch (Exception $e) 
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            }
            elseif(core::config('general.moderation') == Model_Ad::EMAIL_MODERATION)
            {
                $advert->status = Model_Ad::STATUS_NOPUBLISHED;

                try 
                {
                    $advert->save();
                    Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
                } 
                catch (Exception $e) 
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            }

            $this->redirect(Route::url('ad', array('category'=>$advert->category->seoname, 'seotitle'=>$advert->seotitle)));
        }

    }

	public function action_stats()
   	{
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats')));
        
        Controller::$full_width = TRUE;

        $this->template->scripts['footer'] = array('js/oc-panel/stats/dashboard.js');
        
        $this->template->title = __('Stats');
        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/profile/stats');


        $user    = Auth::instance()->get_user();
        $list_ad = array();
        $advert  = new Model_Ad();

        //single stats for 1 ad
        if (is_numeric($id_ad = $this->request->param('id')))
        {
            $advert = new Model_Ad($id_ad);

            if($advert->loaded())
            {
                if($user->id_user !== $advert->id_user)
                {
                    Alert::set(Alert::ALERT, __("This is not your advertisement."));
                    HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'ads')));
                }

                Breadcrumbs::add(Breadcrumb::factory()->set_title($advert->title));

                // make a list of 1 ad (array), and than pass this array to query (IN).. To get correct visits
                $list_ad[] = $id_ad;
            }
        }

        //we didnt filter by ad, so lets get them all!
        if (empty($list_ad))
        {
            $ads = new Model_Ad();
            $collection_of_user_ads = $ads->where('id_user', '=', $user->id_user)->find_all();

            $list_ad = array();
            foreach ($collection_of_user_ads as $key) {
                // make a list of ads (array), and than pass this array to query (IN).. To get correct visits
                $list_ad[] = $key->id_ad;
            }
        }

        // if user doesn't have any ads
        if(empty($list_ad))
            $list_ad = array(NULL);

        $content->advert = $advert;
        

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

        
        /////////////////////CONTACT STATS////////////////////////////////

        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(contacted) count'))
                        ->from('visits')
                        ->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();

        $contacts_dates = $query->as_array('date');

        //Today 
        $query = DB::select(DB::expr('COUNT(contacted) count'))
                        ->from('visits')
                        ->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_today     = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(contacted) count'))
                        ->from('visits')
                        ->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();
        
        $contacts = $query->as_array();
        $content->contacts_yesterday = (isset($contacts[0]['count']))?$contacts[0]['count']:0; //

        //Last 30 days contacts
        $query = DB::select(DB::expr('COUNT(contacted) count'))
                        ->from('visits')
                        ->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_month = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        //total contacts
        $query = DB::select(DB::expr('COUNT(contacted) count'))
        				->where('contacted', '=', 1)
                        ->where('id_ad', 'in', $list_ad)
                        ->from('visits')
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_total = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        /////////////////////VISITS STATS////////////////////////////////

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
 
        $stats_daily = array();
        foreach ($dates as $date) 
        {
            $count_contants = (isset($contacts_dates[$date['date']]['count']))?$contacts_dates[$date['date']]['count']:0;
            $count_visits = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;
            
            $stats_daily[] = array('date'=>$date['date'],'views'=> $count_visits, 'contacts'=>$count_contants);
        } 

        $content->stats_daily = $stats_daily;

        //Today 
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        
                        ->where('id_ad', 'in', $list_ad)
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_today     = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        
                        ->where('id_ad', 'in', $list_ad)
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();
        
        $visits = $query->as_array();
        $content->visits_yesterday = (isset($visits[0]['count']))?$visits[0]['count']:0;


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
