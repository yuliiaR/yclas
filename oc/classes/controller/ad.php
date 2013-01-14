<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	
	/**
	 * 
	 * Display single ad
	 * @throws HTTP_Exception_404
	 */
	public function action_view()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
				 ->limit(1)->find();
			
			if ($ad->loaded())
			{
			
				$do_hit = $ad->count_ad_hit($ad->id_ad, $ad->id_user); // hits counter

				//count how many matches are found 
		        $hits = new Model_Visit();
		        $hits->find_all();
		        $hits->where('id_ad','=', $ad->id_ad)->and_where('id_user', '=', $ad->id_user); 

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/post/single',array('ad'=>$ad, 'hits'=>$hits->count_all()));

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
	
	public function action_edit()
	{
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
				 ->limit(1)->find();
			
			if ($ad->loaded())
			{

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/post/single',array('ad'=>$ad));
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
	 * Create new Adverizment
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

		
		//ad submited
		if ($this->request->ad())
		{
			//form validation
			//recaptcha validation, if recaptcha active
			
			//check account exists
				//if exists send email to activate ad
				//if not exists create account and send email to confirm
				
			//save ad data
			
			//save images, shrink and move to folder /upload/2012/11/25/pics/
			
		}
		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/new');
		$this->template->content->text = Text::bb2html($this->request->ad('description'),TRUE);
	}



	/**
	 * Serp of ads
	 */
	public function action_listing()
	{ $previous_name = session_name("WebsiteID");
		$this->template->bind('content', $content);
	    $this->template->content = View::factory('pages/post/listing',$this->action_list_logic());
	   echo $previous_name;
 	}

	public function action_list_logic()
	{
		/// comon
		$slug_cat = $this->request->param('category',NULL);
		$slug_loc = $this->request->param('location',NULL);
		$page = $this->request->query('p',NULL);
		

		//if everything null redirect to home??@todo
		
		// tmp code for printing all ads
		$cat = new Model_Category();
		$loc = new Model_Location();
		$sidebarCat = $cat->find_all(); // get all to print at sidebar view
		$sidebarLoc = $loc->find_all(); // get all to print at sidebar view
		// print_r($sidebarLoc) ;
		//getting published ads
		$ads = new Model_Ad();

		$ads->where('ad.status', '=', Model_Ad::STATUS_PUBLISHED);
		$ads->find_all();
	
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
		
		$_search_ad = ORM::factory('ad');
		
		// get number of ads filtered, if no filtering, get all
		if($slug_cat && $slug_loc !== NULL){ //both parameters are valid, or combination of them is doesnt exist
			$_search_ad->where('ad.id_category', '=', $category->id_category)
                        		->and_where('ad.id_location', '=', $location->id_location);
			
			if($_search_ad->loaded()){
				$res_count = 0;
			}else{
            	$res_count = $_search_ad->count_all();
            	echo 'res_count'.$res_count.' :';	
			} 
		}
		else if ($slug_cat !== NULL) // category provided
		{
			$_search_ad->where('ad.id_category', '=', $category->id_category);
			$res_count = $_search_ad->count_all();	
		}
		else if($slug_cat == NULL && $slug_loc !== NULL) // category is missing
		{
			$_search_ad->where('ad.id_location', '=', $location->id_location);
			$res_count = $_search_ad->count_all();

			Alert::set(Alert::ERROR, __('Category does\'t exists'));
		}
		else
		{
			$res_count = $ads->count_all();
		}
		
		/*
			PAGINATION 
		 */
		
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> 5
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                    'category'			=> $slug_cat,
                    'location'			=> $slug_loc,
    	    ));
  
			//filter category
			if ($slug_loc && $slug_cat !== NULL)
			{
				$ads = $ads->where('ad.id_category','=',$category->id_category)
								->and_where('ad.id_location','=', $location->id_location)
								->order_by('created','desc')
                            	->limit($pagination->items_per_page)
                            	->offset($pagination->offset)
                            	->find_all();
			}
			else if($slug_cat !== NULL)
			{
				$ads = $ads->where('ad.id_category','=',$category->id_category)
								->order_by('created','desc')
                            	->limit($pagination->items_per_page)
                            	->offset($pagination->offset)
                            	->find_all();
			}	
			else if($slug_cat == NULL && $slug_loc !== NULL)
			{
				$ads = $ads->where('ad.id_location','=',$location->id_location)
								->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();
			}
			else 
			{
				$ads = $ads->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();
			}

		}
		else
		{
			//trow 404 Exception
			throw new HTTP_Exception_404();
		}
		return array('ads'=>$ads,'pagination'=>$pagination,'sidebarCat'=>$sidebarCat,'sidebarLoc'=>$sidebarLoc);
	}
	

	/**
	 * 
	 * NEW ADVERTIZMENT 
	 * 
	 */
	public function action_new()
	{
		//template header
		$this->template->title           	= __('Publish new advertisement');
		$this->template->meta_description	= __('Publish new advertisement');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		//$this->template->scripts['footer'][]= 'js/autogrow-textarea.js';
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';
		
		$category = new Model_Category();
		$location = new Model_Location();
		$user = new Model_User();
		
		//find all, for populating form select fields 
		$_cat = $category->find_all();
		$_loc = $location->find_all();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/new', array('_cat'		=> $_cat,
																		 '_loc' 	=> $_loc,));
		
		// post attributes
		$_auth			= 	Auth::instance(); 
		$title 			= 	$this->request->post('title');
		$seotitle 		= 	$this->request->post('title'); 		// need to do some validation and checking with seotitle !!!
		$cat 			= 	$this->request->post('category');
		$loc 			= 	$this->request->post('location');
		$description 	= 	$this->request->post('description');
		$price 			= 	$this->request->post('price');
		$address 		= 	$this->request->post('address');
		$phone 			= 	$this->request->post('phone');


		////////////////
		// do user check 
		// TO DO ...
		/////////////// 
		if (!$_auth->logged_in()) // this part is for users that are not logged, not finished !!!
			{
				/////////////////////////////////////////////////////
				// check flow . If it goes to moderation or payment
				// TO DO ..
				/////////////////////////////////////////////////////
				
				///////////////////////////
				// creat user if !exists
				// TO DO..
				///////////////////////////
				

				$name 		= $this->request->post('name');
				$email		= $this->request->post('email');
				$pass		= 'new'.rand(1000,90000);	// generate new user password, bad solution find better !!!
				$id_role	= 1;

				
				if (Valid::email($email,TRUE))
				{
					////////////////////
					// make pass check
					// from db
					// TO DO ...
					// ////////////////
					$user = $user->where('email', '=', $email)
							->limit(1)
							->find();

					if ($user->loaded())
					{
						echo "User already exists";
					}
					else
					{ 

						$user->email 	= $email;
						$user->name 	= $name;
						$user->status 	= Model_User::STATUS_ACTIVE;
						$user->id_role	= 1;//normal user
						$user->password = $pass;

						try
						{
							$user->save();
							
							// email::send("root@slobodantumanitas-System", 
							// 			"root@slobodantumanitas-System", 
							// 			"new_user",
							// 			'welcome '.$name.' with pass '.$pass,
							// 			NULL);
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

						$usr = $user->id_user; 
					
				}
					
				

			}
			else
			{
				$usr 		= $_auth->get_user()->id_user; 		// returns and error if user not loged in !!! check that
				$name 		= $_auth->get_user()->name;
				$email 		= $_auth->get_user()->email;
			}	
		
		$_new_ad = ORM::factory('ad');
		$_new_ad->where('title', '=', $title)->find();
		
		// check existance of ad element
		if ($_new_ad->loaded()){
			Alert::set(Alert::ERROR, __('This ad already exist'));
		}
		else if($this->request->post()) //post submition  
		{
		
			if(Valid::not_empty($title) AND Valid::not_empty($description))
			{		
				
				//insert data
				$seotitle = $title.$_new_ad->count_all(); // bad solution, find better !!! 

				$_new_ad->title 		= $title;
				$_new_ad->id_location 	= $loc;
				$_new_ad->id_category 	= $cat;
				$_new_ad->id_user 		= $usr;
				$_new_ad->description 	= $description;
				$_new_ad->type 			= '0';
				$_new_ad->seotitle 		= $seotitle;	 
				$_new_ad->status 		= '1';									// need to be 0, in production 
				$_new_ad->price 		= $price; 								
				$_new_ad->adress 		= $address;
				$_new_ad->phone			= $phone; 
				

				// image upload
				$error_message = NULL;
	    		$filename = NULL;
	    		
	    		if (isset($_FILES['image1']))
	        	{
	        		//$foldername = $title.date(y/m/d/h/m/s); // make unique folder name $seotitle + timestamp
	            	$filename = $this->_save_image($_FILES['image1'], $seotitle);

	        	}
	        	if ( ! $filename)
		        {
		            $error_message = 'There was a problem while uploading the image.
		                Make sure it is uploaded and must be JPG/PNG/GIF file.';

		                echo $error_message;
		        }

			   /////////////
			   // ADD capcha
			   // TO DO..
			   // //////////
			   
			try
				{
					$_new_ad->save();
					
					//$this->_send_mail($title, $name, $email, $_auth); // send mail to user
					email::send("root@slobodantumanitas-System", 
								"root@slobodantumanitas-System", 
								$name, 
								$name." has created new post with title: ".$title, 
								NULL); // send to administrator , check other solution !!!   
					$this->request->redirect(Route::url('default')); 
					  
				}
				catch (ORM_Validation_Exception $e)
				{
					Form::errors($content->errors);
				}
				catch (Exception $e)
				{echo $e;
					throw new HTTP_Exception_500($e->getMessage());

				}
		}
		else
		{ 
			Alert::set(Alert::ALERT, __('Fields Missing'));
		}

			///////////////////////////
			// do error hendling 
			// and presentation of them
			// TO DO...
			///////////////////////////
			
			////////////////////////////////////
			// create HISTORY.xml file
			// for keeping track of changes made
			// TO DO...
			// ///////////////////////////////// 
			
			//recaptcha validation, if recaptcha active
			
				
			
		}
		
		
		

		$this->template->content->text = Text::bb2html($this->request->post('description'),TRUE);	
 	}

 	public function _save_image($image, $seotitle)
 	{
 		////////////////////////////////////
 		// find solutin for dynamic resizing 
 		// dependant on template 
 		// SAVE ONE ORIGINAL AND ONE CUSTOM
 		// TO DO...
 		// ///////////////////////////////// 

 		if (
            ! Upload::valid($image) OR
            ! Upload::not_empty($image) OR
            ! Upload::type($image, array('jpg', 'jpeg', 'png', 'gif')))
        {
            return FALSE;
 		}
 		$path = $this->_image_path($seotitle);
 		
 		$directory = DOCROOT.$path;

 		if ($file = Upload::save($image, NULL, $directory))
        {
            $filename = strtolower(Text::random('alnum', 20)).'.jpg';
 
            Image::factory($file)
                ->resize(200, 200, Image::AUTO)
                ->save($directory.$filename);
 
            // Delete the temporary file
            unlink($file);
 
            return $filename;
        }
 
        return FALSE;
    }
   
    public function _image_path($seotitle)
    {

    	///////////////////////////////////
    	// make path creation from DB oject
    	// TO DO ...
    	// ////////////////////////////////
    	 
    	$date = date('y/m/d');

		$parse_data = explode("/", $date); 			// make array with date values
		
		$path = "upload/"; // root upload folder

		for ($i=0; $i < count($parse_data); $i++) { 
			$path .= $parse_data[$i].'/'; 			// append, to create path 
		}
		if(!is_dir($path .= $seotitle.'/')){ 					// check if path exists 
				mkdir($path, 0755, TRUE);
			}
			echo $path;
		return $path;
    }

    ///////////////////////////////
    // modify _send_email function
    // atm works but not good
    // and it needs improuvment
    ///////////////////////////
  //   public function _send_mail($title, $name, $email, $_auth){
  //   	echo 'blaasdsad';
  //   	//message format
  //       $message = "User: ".$_auth->get_user()->name." created post".PHP_EOL ;
  //       $message.= "With title : ".$title.PHP_EOL;
  //       $message.= "On date".date('d/m/Y').PHP_EOL;
  //       $subject = "User ".$_auth->get_user()->name." created new post";
		
		// if(!$_auth->logged_in()){
			
		// 	email::send("root@slobodantumanitas-System", $email, "New post by user: ".$name, $message, NULL);
		// }
		// else
		// {
		// 	$email = $_auth->get_user()->email;
		// 	email::send("root@slobodantumanitas-System", $email, $subject, $message, NULL);
		// }
  //   }
}// End ad controller
