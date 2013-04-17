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
		
		//scripts	
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
		$this->template->scripts['footer'][]= 'js/chosen.jquery.min.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';
		
		$category 	= new Model_Category();
		$location 	= new Model_Location();
		$user 		= new Model_User();
		
		//find all, for populating form select fields 
		$_cat = $category->find_all();
		$_loc = $location->find_all();
		$children_categ = $category->get_category_children();
	
		$form_show = array('captcha'	=>core::config('advertisement.captcha'),
						   'website'	=>core::config('advertisement.website'),
						   'phone'		=>core::config('advertisement.phone'),
						   'location'	=>core::config('advertisement.location'),
						   'address'	=>core::config('advertisement.address'),
						   'price'		=>core::config('advertisement.price'));

		//render view publish new
		$this->template->content = View::factory('pages/ad/new', array('_cat'				=> $_cat,
																	   '_loc' 				=> $_loc,
																	   'children_categ'		=> $children_categ,
																	   'form_show'			=> $form_show));
		
		// $_POST array with all fields 
		$data = array(	'_auth' 		=> $auth 		= 	Auth::instance(),
						'title' 		=> $title 		= 	$this->request->post('title'),
						'cat'			=> $cat 		= 	$this->request->post('category'),
						'loc'			=> $loc 		= 	$this->request->post('location'),
						'description'	=> $description = 	$this->request->post('description'),
						'price'			=> $price 		= 	$this->request->post('price'),
						'address'		=> $address 	= 	$this->request->post('address'),
						'phone'			=> $phone 		= 	$this->request->post('phone'),
						'website'		=> $website 	= 	$this->request->post('website'),
						'user'			=> $user
						); 
		
		// depending on user flow (moderation mode), change usecase
		$moderation = core::config('general.moderation'); 
		if ($moderation == 0)
		{
			if (Core::config('sitemap.on_post') == TRUE)
				// Sitemap::generate(); // @TODO CHECK WHY DOESNT WORK

			$status = Model_Ad::STATUS_PUBLISHED;
			$this->_save_new_ad($data, $status, $published = TRUE, $moderation, $form_show['captcha']);

		}
		else if($moderation == 1 || $moderation == 2 || $moderation == 3)
		{
			$status = Model_Ad::STATUS_NOPUBLISHED;
			$this->_save_new_ad($data, $status, $published = FALSE, $moderation, $form_show['captcha']);
		}

			
 	}

 	/**
 	 * [_save_new_ad Save new advertisement if validated, with a given parameters 
 	 * 
 	 * @param  [array] $data   [post values]
 	 * @param  [int] $status [status of advert.]
 	 * @param  [bool] $published [Confirms if advert is published. ref to model_ad]
 	 * @param  [int] $moderation [moderation status/mode]
 	 * @param  [bool] $captcha_show [Chaptcha active/notactive. ref to db config]
 	 * 
 	 * @return [view] View dependant on usecase 
 	 */
 	public function _save_new_ad($data, $status, $published, $moderation, $captcha_show)
 	{
 		
 		// case when user is not logged in. We create new user if he doesnt exists in DB
 		if (!$data['_auth']->logged_in()) 
		{
			
			$name 		= $this->request->post('name');
			$email		= $this->request->post('email');
			$password	= $this->request->post('password');
			$seoname	= URL::title($this->request->post('name'), '-', FALSE);
			
			if (Valid::email($email,TRUE))
			{
				$user = $data['user']->where('email', '=', $email)
						->limit(1)
						->find();

				if ($user->loaded())
				{ //@TODO - check this usecase, why is commented 
					// Alert::set(Alert::SUCCESS, __('User Exists, please login first to authenticate profile'));
					// $this->request->redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')));
					
				}
				else
				{ 
					$user->email 	= $email;
					$user->name 	= $name;
					$user->status 	= Model_User::STATUS_ACTIVE;
					$user->id_role	= 1;//normal user
					$user->password = '1234';	// @TODO generate new user password, bad solution find better !!!
					$user->seoname 	= $seoname;
					
					try
					{
						$user->save();
						Alert::set(Alert::SUCCESS, __('New profile has been created. Welcome ').$name.' !');
						
						//$user->email('newuser'); //this is to static
					}
					catch (ORM_Validation_Exception $e)
					{
						//Form::errors($content->errors);
					}
					catch (Exception $e)
					{
						throw new HTTP_Exception_500($e->getMessage());
					}
				}
					$usr = $data['user']->id_user; 
			}
		}
		else
		{
			$usr 		= $data['_auth']->get_user()->id_user;
			$name 		= $data['_auth']->get_user()->name;
			$email 		= $data['_auth']->get_user()->email;
		}	
		
		$_new_ad = ORM::factory('ad');
		

		//$_POST is submitted for a new ad 
		if($this->request->post()) 
		{
			
			if($captcha_show == FALSE || captcha::check('contact') ) 
			{		
				
				//insert data

				$seotitle = $_new_ad->gen_seo_title($data['title']); 
				
				$_new_ad->title 		= $data['title'];
				$_new_ad->id_location 	= $data['loc'];
				$_new_ad->id_category 	= $data['cat'];
				$_new_ad->id_user 		= $usr;
				$_new_ad->description 	= $data['description'];
				$_new_ad->type 	 		= '0';
				$_new_ad->seotitle 		= $seotitle;	 
				$_new_ad->status 		= $status; 
				$_new_ad->price 		= $data['price']; 								
				$_new_ad->address 		= $data['address'];
				$_new_ad->phone			= $data['phone'];
				$_new_ad->website		= $data['website']; 

				try
				{
					$_new_ad->save();
					
					// if moderation is off update db field with time of creation 
					if($published)
					{	
						$_ad_published = new Model_Ad();
						$_ad_published->where('seotitle', '=', $seotitle)->limit(1)->find();
						$_ad_published->published = $_ad_published->created;
						$_ad_published->save();
						$created = $_ad_published->created;
					}
					else 
					{
						$created = new Model_Ad();
						$created = $created->where('seotitle', '=', $seotitle)->limit(1)->find(); 
						$created = $created->created;
					}
					//$user->email('newadvertisement'); // @TODO send email
					  
				}
				catch (ORM_Validation_Exception $e)
				{
					Form::errors($content->errors);
				}
				catch (Exception $e)
				{
					throw new HTTP_Exception_500($e->getMessage());
				}
				

				// image upload,
				// in case something wrong happens user is redirected to edit advert. 
				$error_message = NULL;
	    		$filename = NULL;
	    		$counter = 0;
	    		for ($i=0; $i < core::config("advertisement.num_images"); $i++) { 
	    			$counter++;

	    			if (isset($_FILES['image'.$i]))
	        		{
		        		$img_files = array($_FILES['image'.$i]);

		            	$filename = $_new_ad->save_image($img_files, $_new_ad->id_ad, $created, $_new_ad->seotitle, $counter);

	        		}

	        		if ($filename['error'] == TRUE)
		       		{
			        	$_new_ad->has_images = 1;
		        	}
		        	
		        	if($filename['error_name'] == "wrong_format" || $filename['error_name'] == 'upload_unsuccessful')
		        	{
		        		Alert::set(Alert::ALERT, __('Something went wrong with uploading pictures, please check format'));

		        		$this->request->redirect(Route::url('default', array('controller'=>'ad','action'=>'update','id'=>$_new_ad->id_ad)));
		        	}

		        	try {
		        		$_new_ad->save();
		        	} catch (Exception $e) {
		        		Alert::set(Alert::ALERT, __('Something went wrong with uploading pictures'));
		        		$this->request->redirect(Route::url('default', array('controller'=>'ad','action'=>'update','id'=>$ad->id_ad)));
		        	}
	    		}

				// PAYMENT METHOD ACTIVE
				if($moderation == 2 || $moderation == 3)
				{
					$payment_order = new Model_Order();
					$order_id = $payment_order->make_new_order($data, $usr, $seotitle);

					if($order_id == NULL)
					{
						$this->request->redirect(Route::url('default'));
					}
					// redirect to payment
        			$this->request->redirect(Route::url('default', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $order_id))); // @TODO - check route
				}
				else
				{
					Alert::set(Alert::SUCCESS, __('Advertisement is posted. Congratulations!'));
					$this->request->redirect(Route::url('default'));
				} 
			}
			else
			{ 
				Alert::set(Alert::ALERT, __('Captcha is not correct'));
			}
		}
 	}

}
