<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CONTROLLER NEW 
 */
class Controller_New extends Controller
{
	
	/**
	 * 
	 * NEW ADVERTISEMENT 
	 * 
	 */
	public function action_index()
	{

		//template header
		$this->template->title           	= __('Publish new advertisement');
		$this->template->meta_description	= __('Publish new advertisement');
		
		
		$this->template->styles = array('http://cdn.jsdelivr.net/sceditor/1.4.3/themes/default.min.css' => 'screen',
										'css/jasny-bootstrap.min.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery.sceditor.min.js';
        $this->template->scripts['footer'][] = 'js/jquery.validate.min.js';
        $this->template->scripts['footer'][] = 'js/jasny-bootstrap.min.js';
        $this->template->scripts['footer'][] = 'js/jquery.chained.min.js';
        if(core::config('advertisement.map_pub_new'))
        {
	        $this->template->scripts['footer'][] = 'http://maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7';
	        $this->template->scripts['footer'][] = 'http://cdn.jsdelivr.net/gmaps/0.4.4/gmaps.js';
        }
        $this->template->scripts['footer'][] = 'js/new.js';

        // redirect to login, if conditions are met 
        if(core::config('advertisement.login_to_post') == TRUE AND !Auth::instance()->logged_in())
		{
			Alert::set(Alert::INFO, __('Please, login before posting advertisement!'));
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')));
		}

		//find all, for populating form select fields 
		$categories         = Model_Category::get_as_array();  
        $order_categories   = Model_Category::get_multidimensional();
        $order_parent_deep  = Model_Category::get_by_deep();

		// NO categories redirect ADMIN to categories panel
		if(count($order_categories) == 0)
		{
			if(Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN)
			{
				Alert::set(Alert::INFO, __('Please, first create some categories.'));
				$this->redirect(Route::url('oc-panel',array('controller'=>'category','action'=>'index')));
			}
			else
			{
				Alert::set(Alert::INFO, __('Posting advertisements is not yet available.'));
				$this->redirect('default');
			}
		}

        //get locations
        $locations         = Model_Location::get_as_array();  
        $order_locations   = Model_Location::get_multidimensional();
        $loc_parent_deep   = Model_Location::get_by_deep();
		
		// bool values from DB, to show or hide this fields in view
		$form_show = array('captcha'	=>core::config('advertisement.captcha'),
						   'website'	=>core::config('advertisement.website'),
						   'phone'		=>core::config('advertisement.phone'),
						   'location'	=>core::config('advertisement.location'),
						   'address'	=>core::config('advertisement.address'),
						   'price'		=>core::config('advertisement.price'));
		
		//Detect early spam users, show him alert
		$auth_user = Auth::instance();
		if(core::config('general.black_list') AND 
		   	$auth_user->logged_in() AND $auth_user->get_user()->status == Model_User::STATUS_SPAM)
		{
			Alert::set(Alert::ALERT, __('Your profile has been disable for posting, due to recent spam content! If you think this is a mistake please contact us.'));
			$this->redirect('default');
		}
		
		$id_category = NULL;
        $selected_category = new Model_Category();
        //if theres a category by post or by get
        if (Core::request('category')!==NULL)
        {
            if (is_numeric(Core::request('category')))
                $selected_category->where('id_category','=',core::request('category'))->limit(1)->find();
            else
                $selected_category->where('seoname','=',core::request('category'))->limit(1)->find();

            if ($selected_category->loaded())
                $id_category = $selected_category->id_category;
        }


        $selected_location = new Model_Location();
        //if theres a location by post or by get
        if (Core::request('location')!==NULL)
        {
            if (is_numeric(Core::request('location')))
                $selected_location->where('id_location','=',core::request('location'))->limit(1)->find();
            else
                $selected_location->where('seoname','=',core::request('location'))->limit(1)->find();
        }

		//render view publish new
		$this->template->content = View::factory('pages/ad/new', array('categories'		    => $categories,
                                                                       'order_categories'   => $order_categories,
																	   'order_parent_deep'  => $order_parent_deep,
																	   'locations' 			=> $locations,
                                                                       'order_locations'    => $order_locations,
                                                                       'loc_parent_deep'	=> $loc_parent_deep,
																	   'form_show'			=> $form_show,
																	   'id_category'		=> $id_category,
                                                                       'selected_category'  => $selected_category,
                                                                       'fields'             => Model_Field::get_all()));
		if ($_POST) 
        {
        	
        	$validation = Validation::factory($this->request->post())

                ->rule('title', 'not_empty')
                ->rule('title', 'min_length', array(':value', 2))
                ->rule('title', 'max_length', array(':value', 145))

                ->rule('description', 'not_empty')
                ->rule('description', 'min_length', array(':value', 5))
    
                ->rule('category', 'not_empty')
                ->rule('category', 'numeric');

            if(!Auth::instance()->logged_in())
    		{
            	$validation = $validation->rule('email', 'not_empty')
            	->rule('email', 'email')

            	->rule('name', 'not_empty')
            	->rule('name', 'min_length', array(':value', 2))
            	->rule('name', 'max_length', array(':value', 145));
            }

            if(core::config('advertisement.location'))
            {
            	if(count($locations) > 1)
	            	$validation = $validation->rule('location', 'not_empty')
	            	->rule('location', 'numeric');
            }
	        
	        if($validation->check())
        	{       

	            // $_POST array with all fields 
	            $data = array(  'title'         => $title       =   $this->request->post('title'),
	                            'cat'           => $cat         =   $this->request->post('category'),
	                            'loc'           => $loc         =   $this->request->post('location'),
	                            'description'   => $description =   $this->request->post('description'),
	                            'price'         => $price       =   $this->request->post('price'),
	                            'address'       => $address     =   $this->request->post('address'),
	                            'phone'         => $phone       =   $this->request->post('phone'),
	                            'website'       => $website     =   $this->request->post('website'),
	                            'stock'       	=> $stock     	=   $this->request->post('stock')
	                            ); 
	            
	            // append to $data new custom values
	            foreach ($_POST as $name => $field) 
	            {
	            	// get by prefix
					if (strpos($name,'cf_') !== false) 
					{
						$data[$name] = $field;
						//checkbox when selected return string 'on' as a value
						if($field == 'on')
							$data[$name] = 1;
						if(empty($field))
							$data[$name] = NULL;

					}
	        	}
		
	            // depending on user flow (moderation mode), change usecase
	            $moderation = core::config('general.moderation'); 

	            if ($moderation == Model_Ad::POST_DIRECTLY) // direct post no moderation
	            {

	                if (Core::config('sitemap.on_post') == TRUE)
	                    Sitemap::generate(); 

	                $status = Model_Ad::STATUS_PUBLISHED;
	                $this->save_new_ad($data, $status, TRUE, $moderation, $form_show['captcha']);

	            }
	            elseif( $moderation == Model_Ad::MODERATION_ON 
	                 || $moderation == Model_Ad::PAYMENT_ON 
	                 || $moderation == Model_Ad::EMAIL_CONFIRMATION 
	                 || $moderation == Model_Ad::EMAIL_MODERATION 
	                 || $moderation == Model_Ad::PAYMENT_MODERATION)
	            {
	                $status = Model_Ad::STATUS_NOPUBLISHED;
	                $this->save_new_ad($data, $status, FALSE, $moderation, $form_show['captcha']);
	            }
	        }
	        else
	        {
	        	$errors = $validation->errors('ad');
	        	foreach ($errors as $f => $err) 
                {
	        		Alert::set(Alert::ALERT, $err);
	        	}
	        }
        }
		
 	}

 	/**
 	 * [save_new_ad Save new advertisement if validated, with a given parameters 
 	 * 
 	 * @param  [array] $data   [post values]
 	 * @param  [int] $status [status of advert.]
 	 * @param  [bool] $published [Confirms if advert is published. ref to model_ad]
 	 * @param  [int] $moderation [moderation status/mode]
 	 * 
 	 * @return [view] View dependant on usecase 
 	*/
 	public function save_new_ad($data, $status, $published, $moderation)
 	{

		//$_POST is submitted for a new ad 
		if($this->request->post()) 
		{
			if(captcha::check('publish_new')) 
			{		
                $new_ad     = new Model_Ad();

				//FORM DATA 
				$seotitle = $new_ad->gen_seo_title($data['title']); 
				
				$new_ad->title 			= Text::banned_words($data['title']);
				$new_ad->id_location 	= $data['loc'];
				$new_ad->id_category 	= $data['cat'];
				$new_ad->description 	= Text::banned_words($data['description']);
				$new_ad->seotitle 		= $seotitle;	 
				$new_ad->status 		= $status; 
				$new_ad->price 			= floatval(str_replace(',', '.', $data['price'])); 								
				$new_ad->address 		= $data['address'];
				$new_ad->phone			= $data['phone'];
				$new_ad->website		= $data['website'];
				$new_ad->stock			= $data['stock']; 
				
				// set custom values
				foreach ($data as $name => $field) 
	            {
	            	// get only custom values with prefix
					if (strpos($name,'cf_') !== false)
						$new_ad->$name = $field;
	        	}

	        	// User detection, if doesnt exists create
		 		if (!Auth::instance()->logged_in()) 
					$user = Model_User::create_email(core::post('email'), core::post('name'));
				else
                    $user = Auth::instance()->get_user();

                $user_id    = $user->id_user;
                $name       = $user->name;
                $email      = $user->email;
				
				//Do not allow posting! Case where we detect spam user, that are not logged in. 
				if(core::config('general.black_list'))
				{
					if(!$user->loaded() AND $user->is_spam($email))
					{
						Alert::set(Alert::ALERT, __('Your profile has been disable for posting, due to recent spam content! If you think this is a mistake please contact us.'));
						$this->redirect('default');	
					}
				}

				// SAVE AD
				$new_ad->id_user = $user_id; // after handling user

					
				//akismet spam filter
				if(core::akismet($data['title'], $email, $data['description']) == TRUE)
				{
					// is user marked as spammer? Make him one :)
					if(core::config('general.black_list'))
					   $user->user_spam();
					
					Alert::set(Alert::SUCCESS, __('This post has been considered as spam! We are sorry but we cant publish this advertisement.'));
					$this->redirect('default');
				}//akismet

                //status of the ad
                if($moderation == Model_Ad::EMAIL_MODERATION OR $moderation == Model_Ad::EMAIL_CONFIRMATION)
                    $new_ad->status = Model_Ad::STATUS_UNCONFIRMED;
                
                
                $new_ad->created = Date::unix2mysql();
                // if moderation is off update db field with time of creation 
                if($published)
                    $new_ad->published = $new_ad->created;

                //save the ad
                try 
                {
                    $new_ad->save();
                } 
                catch (Exception $e) 
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
				
                $edit_url   = Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$new_ad->id_ad));
                $delete_url = Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$new_ad->id_ad));

				// after successful posting send them email depending on moderation
				if($moderation == Model_Ad::EMAIL_CONFIRMATION OR 
				   $moderation == Model_Ad::EMAIL_MODERATION)
				{
					//we get the QL, and force the regen of token for security
                	$url_ql = $user->ql('default',array( 'controller' => 'ad', 
                                                      	 'action'     => 'confirm_post',
                                                      	 'id'		  => $new_ad->id_ad),TRUE);
                	
                	
                	$ret = $user->email('ads_confirm',array('[URL.QL]'=>$url_ql,
                											'[AD.NAME]'=>$new_ad->title,
                											'[URL.EDITAD]'=>$edit_url,
                											'[URL.DELETEAD]'=>$delete_url));
                	
				}
				elseif($moderation == Model_Ad::MODERATION_ON)
				{
					//we get the QL, and force the regen of token for security
                	$url_ql = $user->ql('oc-panel',array( 'controller'=> 'profile', 
                                                      	  'action'    => 'update',
                                                      	  'id'		  => $new_ad->id_ad),TRUE);

                	$ret = $user->email('ads_notify',array('[URL.QL]'		=>$url_ql,
                										   '[AD.NAME]'		=>$new_ad->title,
                										   '[URL.EDITAD]'	=>$edit_url,
                										   '[URL.DELETEAD]'	=>$delete_url)); // email to notify user of creating, but it is in moderation currently 
				}
				elseif($moderation == Model_Ad::POST_DIRECTLY)
				{
					$url_cont = $user->ql('contact', array(),TRUE);
					$url_ad = $user->ql('ad', array('category'=>$data['cat'],
		                							'seotitle'=>$seotitle), TRUE);

					$ret = $user->email('ads_user_check',array('[URL.CONTACT]'	=>$url_cont,
		                										'[URL.AD]'		=>$url_ad,
		                										'[AD.NAME]'		=>$new_ad->title,
		                										'[URL.EDITAD]'	=>$edit_url,
                										   		'[URL.DELETEAD]'=>$delete_url));
				}


				// new ad notification email to admin (notify_email), if set to TRUE 
				if(core::config('email.new_ad_notify') == TRUE)
				{
                    $url_ad = Route::url('ad', array('category'=>$data['cat'],'seotitle'=>$seotitle));
					
					$replace = array('[URL.AD]'    	   =>$url_ad,
                                  	 '[AD.TITLE]'  	   =>$new_ad->title);

	                Email::content(core::config('email.notify_email'),
	                                    core::config('general.site_name'),
	                                    core::config('email.notify_email'),
	                                    core::config('general.site_name'),'ads_to_admin',
	                                    $replace);
	            }


				// IMAGE UPLOAD 
				// in case something wrong happens user is redirected to edit advert. 
	    		$filename = NULL;

	    		for ($i=0; $i < core::config("advertisement.num_images"); $i++) 
	    		{ 
	    			if (isset($_FILES['image'.$i]))
		            	$filename = $new_ad->save_image($_FILES['image'.$i]);
	        			        		
	        		if ($filename)
                    {
			        	$new_ad->has_images = 1;
			        	try {
							$new_ad->save();
						} 
						catch (Exception $e){
							throw HTTP_Exception::factory(500,$e->getMessage());
						}
	        		}
		        }

                //calculate how much he need to pay in case we have payment on
                if ($moderation == Model_Ad::PAYMENT_ON OR $moderation == Model_Ad::PAYMENT_MODERATION)
                {
                    // check category price, if 0 check parent
                    if($new_ad->category->price == 0)
                    {
                        $cat_parent = new Model_Category($new_ad->category->id_category_parent);

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
                        $amount = $new_ad->category->price;
                }

                //where and what we say to the user depending ont he moderation
                switch ($moderation) 
                {
                    case Model_Ad::PAYMENT_ON:
                    case Model_Ad::PAYMENT_MODERATION:
                            $order = Model_Order::new_order($new_ad, $new_ad->user, Model_Order::CATEGORY_PRODUCT, $amount, NULL, 'category product');
                            // redirect to invoice
                            $this->redirect(Route::url('default', array('controller'=> 'ad','action'=>'invoice' , 'id' => $order->id_order)));
                        break;

                    case Model_Ad::EMAIL_MODERATION:
                    case Model_Ad::EMAIL_CONFIRMATION:
                            Alert::set(Alert::INFO, __('Advertisement is posted but first you need to activate. Please check your email!'));
                        break;

                    case Model_Ad::MODERATION_ON:
                            Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
                        break;
                    
                    case Model_Ad::POST_DIRECTLY:
                    default:
                            Model_Subscribe::find_subscribers($data, floatval(str_replace(',', '.', $data['price'])), $seotitle, $email);
                            Alert::set(Alert::SUCCESS, __('Advertisement is posted. Congratulations!'));
                        break;
                }

                $this->redirect(Route::url('default'));

			}//captcha
			else
			{ 
				Alert::set(Alert::ALERT, __('Captcha is not correct'));
			}
		}//is post

 	}// save new ad

}
