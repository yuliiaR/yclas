<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	
	/**
	 * Publis all adver.-s without filter
	 */
	public function action_index()
	{ 
		//$this->template->bind('content', $content);
		
		$advert 	= new Model_Ad();
		$slug_cat 	= new Model_Category();
		$slug_loc 	= new Model_Location();

		$cat = $slug_cat->find_all();
		$loc = $slug_loc->find_all();

		
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
			$this->template->content = View::factory('pages/post/listing');

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
	                    'items_per_page' 	=> 5
	        ))->route_params(array(
	                    'controller' 		=> $this->request->controller(),
	                    'action'     	 	=> $this->request->action(),
	        ));

	        if ($res_count>0)
	        {
	        	$content->ads = $ads->order_by('created','desc')
	                                ->limit($pagination->items_per_page)->offset($pagination->offset)->find_all();
	                                   	            
	        } else $content->ads = NULL;
	       	

			$content->pagination = $pagination->render();
			$content->cat = $cat;
			$content->loc = $loc;
			$content->user = $user;
			$content->img_path = NULL;
        }
        // advansed search with many parameters
        else if($this->request->query('advert') 
        	 	|| $this->request->query('cat') 
        	 	|| $this->request->query('loc'))
        {
        	// variables 
        	$search_advert 	= $this->request->query('advert');
        	$search_cat 	= $this->request->query('cat');
        	$search_loc 	= $this->request->query('loc');
        	
        	$ads = $this->action_advansed_search($search_advert, $search_cat, $search_loc); // logic
        	
        	$this->template->bind('content', $content);
			$this->template->content = View::factory('pages/post/listing');

			$res_count = count($ads);

	        $pagination = Pagination::factory(array(
	                    'view'           	=> 'pagination',
	                    'total_items'      	=> $res_count,
	                    'items_per_page' 	=> 5
	        ))->route_params(array(
	                    'controller' 		=> $this->request->controller(),
	                    'action'     	 	=> $this->request->action(),
	        ));

	        $content->ads = $ads = $ads->order_by('created','desc')
	                   				   ->limit($pagination->items_per_page)
	                   				   ->offset($pagination->offset)
	                   				   ->find_all();

			// view variables
			$content->pagination = $pagination->render();
			$content->cat = $cat;
			$content->loc = $loc;
			$content->user = $user;
			$content->img_path = NULL;
        }
        // list all
        else
       	{
	   		$data = $this->action_list_logic();
			$this->template->bind('content', $content);
			$this->template->content = View::factory('pages/post/listing',$data);
            $search_term = $this->request->param('search',NULL);
        }
 	}

 	/**
 	 * [action_list_logic Returnes arrays with necessary data to publis advert.-s]
 	 * @return [array] [$ads, $pagination, $user, $image_path]
 	 */
	public function action_list_logic()
	{
		$ads = new Model_Ad();
		
		$cat = new Model_Category();
		$cat = $cat->find_all();
		
		$loc = new Model_Location();
		$loc = $loc->find_all();
		
		$res_count = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)->count_all();
		
		// check if there are some advet.-s
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> 5
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                 
    	    ));
    	    $ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED)
    	    					->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();
			}
		else
		{
			//trow 404 Exception
			throw new HTTP_Exception_404();
		}

		if(Auth::instance()->get_user() == NULL)
		{
			$user = NULL;
		}
		else
		{
			$user = Auth::instance()->get_user();
		}

		
		///////////////////
		// BAD SOLUTION
		//
		// DO BETER!!!!!!!!		
		//////////////////


		// return image path 
		$img_path = array();
		
		foreach ($ads as $a) {

			if ($a->has_images == 1)
			{
	
				if(is_array($path = $this->_image_path($a)))
				{
					$path = $this->_image_path($a);
					array_push($img_path, $path);
				}else
				{
					$path = NULL;
					array_push($img_path, "bla");	
				} 
				
				
			}
		} 
		
		return array('ads'			=> $ads,
					 'pagination'	=> $pagination, 
					 'user'			=> $user, 
					 'img_path' 	=> $img_path,
					 'cat'			=> $cat,
					 'loc'			=> $loc);
	}
	

	/**
	 * [action_search filter results]
	 * @param  [string] $search_term [search result]
	 * @return [view]              	 [filtered ads]
	 */
	public function action_advansed_search($advert, $cat, $loc)
	{	
		$res = new Model_Ad();
		$res->where('status', '=', Model_Ad::STATUS_PUBLISHED);

		if($advert != NULL)
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
		$seotitle = $this->request->param('seotitle',NULL);
		$category = $this->request->param('category');
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
				 ->limit(1)->find();
			
			$cat = ORM::factory('category', $ad->id_category);
			$loc = ORM::factory('location', $ad->id_location);

			if ($ad->loaded())
			{
				$permission = TRUE; //permission to access advert. 
				if(!Auth::instance()->logged_in() || Auth::instance()->get_user()->id_role != 10)
				{
					$do_hit = $ad->count_ad_hit($ad->id_ad, $ad->id_user); // hits counter
					$permission = FALSE;
				}
				//count how many matches are found 
		        $hits = new Model_Visit();
		        $hits->find_all();
		        $hits->where('id_ad','=', $ad->id_ad)->and_where('id_user', '=', $ad->id_user); 

		        // show image path if they exist
		    	if($ad->has_images == 1)
				{
					$path = $this->_image_path($ad); 
				}else $path = NULL;

		        if($this->request->post()) //message submition  
				{
					if(captcha::check('contact'))
					{ 
						Alert::set(Alert::SUCCESS, __('Success, your message is sent'));
						
						$owner = ORM::factory('user', $ad->id_user);
						
						
						$message = array('name'			=>$this->request->post('name'),
										 'email_from'	=>$this->request->post('email'),
										 'subject'		=>$this->request->post('subject'),
										 'message'		=>$this->request->post('message'),
										 'email_to'		=>$owner->email);

						$advert_owner = new Model_User();
						$advert_owner = $advert_owner->where('id_user', '=', $ad->id_user)->limit(1)->find();
				
						email::send("root@slobodantumanitas-System",$message['email_from'],$message['subject'],$message['message']);
					}
					else
					{
						Alert::set(Alert::ERROR, __('You made some mistake'));
					}
				}	
				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/post/single',array('ad'			=>$ad,
																				   'permission'	=>$permission, 
																				   'hits'		=>$hits->count_all(), 
																				   'path'		=>$path));

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
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= '/js/chosen.jquery.min.js';
		$this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';

		$form = ORM::factory('ad', $this->request->param('id'));
		
		$cat = new Model_Category();
		$cat = $cat->find_all();
		
		$loc = $location = new Model_Location();
		$loc = $loc->find_all();
		
		$locale = new Model_Config();
		$locale = $locale->where('config_key','=','locale')->limit(1)->find();

		if($form->has_images == 1)
		{
			$current_path = $form->_gen_img_path($form->seotitle, $form->created);
			
			$handle = opendir($current_path);
			$count = 0;
			while(FALSE !== ($entry = readdir($handle)))
			{
				if($entry != '.' && $entry != '..') $count++;
			}

			$config = new Model_Config();
			$config->where('config_key','=','num_images')->limit(1)->find();
			$num_images = $config->config_value;

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
			else if($count < $num_images*2) $img_permission = TRUE;
			else $img_permission = FALSE;
			
		}else $img_permission = TRUE;
		
		$path = $this->_image_path($form);
		$this->template->content = View::factory('pages/post/edit', array('ad'			=>$form, 
																		  'location'	=>$loc, 
																		  'category'	=>$cat,
																		  'path'		=>$path,
																		  'perm'		=>$img_permission,
																		  'locale'		=>$locale));
		
		if(Auth::instance()->get_user()->loaded() == $form->id_user 
			|| Auth::instance()->get_user()->id_role == 10)
		{
			if ($this->request->post())
			{
				$data = array(	'_auth' 		=> $auth 		= 	Auth::instance(),
								'title' 		=> $title 		= 	$this->request->post('title'),
								'seotitle' 		=> $seotitle 	= 	$this->request->post('title'),
								'cat'			=> $cat 		= 	$this->request->post('category'),
								'loc'			=> $loc 		= 	$this->request->post('location'),
								'description'	=> $description = 	$this->request->post('description'),
								'price'			=> $price 		= 	$this->request->post('price'),
								'status'		=> $status		= 	$this->request->post('status'),
								'address'		=> $address 	= 	$this->request->post('address'),
								'phone'			=> $phone 		= 	$this->request->post('phone'),
								'has_images'	=> 0,
								'user'			=> $user 		= new Model_User()
								); 

				//insert data
				if ($this->request->post('title') != $form->title)
				{
					if($form->has_images == 1)
					{
						$current_path = $form->_gen_img_path($form->seotitle, $form->created);
						// rename current image path to match new seoname
						rename($current_path, $form->_gen_img_path($form->gen_seo_title($data['title']), $form->created)); 

					}
					$seotitle = $form->gen_seo_title($data['title']);
					$form->seotitle = $seotitle;
					
				}else $form->seotitle = $form->seotitle;
				 
				$form->title 			= $data['title'];
				$form->id_location 		= $data['loc'];
				$form->id_category 		= $data['cat'];
				$form->description 		= $data['description'];
				$form->status 			= $data['status'];									// need to be 0, in production 
				$form->price 			= $data['price']; 								
				$form->adress 			= $data['address'];
				$form->phone			= $data['phone']; 


				///////////////////////////////////
				// TODO:
				// HOW TO WORK WITH TIME 
				// when advert. is republisehed
				// or first time but diff. day etc..
				// ////////////////////////////////
			
				// if($data['status'] == 1 && $form->publis != NULL)
				// {
				// 	$form->published = 
				// }

				$obj_img = new Controller_New($this->request,$this->response);

				// image upload
				$error_message = NULL;
	    		$filename = NULL;
	    		
	    		if (isset($_FILES['image1']) || isset($_FILES['image2']))
	        	{ 
	        		$img_files = array($_FILES['image1'], $_FILES['image2']);
	            	$filename = $obj_img->_save_image($img_files, $form->seotitle, $form->created);
	        	}
	        	if ( $filename !== TRUE)
		        {
		            $error_message = 'There was a problem while uploading the image.
		                Make sure it is uploaded and must be JPG/PNG/GIF file.';

		        } else $form->has_images = 1; // update column has_images if image is added

				try 
				{	
					$form->save();
					Alert::set(Alert::SUCCESS, __('Success, advertisement is updated'));
					$this->request->redirect(Route::url('default',array('controller'=>'home','action'=>'index')));
				
				}
				catch (ORM_Validation_Exception $e)
				{echo $e;
					Form::errors($content->errors);
				}
				catch (Exception $e)
				{echo $e;
					throw new HTTP_Exception_500($e->getMessage());echo $e;
				}
			}
		}
	}

	public function action_img_delete()
	{
		$element = ORM::factory('ad', $this->request->param('id'));

		$img_path = $element->_gen_img_path($element->seotitle, $element->created);

		if (!is_dir($img_path)) 
		{
			return FALSE;
		}
		else
		{	
		
			//delete formated image
			unlink($img_path.$this->request->param('img_name').'.jpg');

			//delete original image
			$orig_img = str_replace('_200x200', '', $this->request->param('img_name'));
			unlink($img_path.$orig_img.'_original.jpg');

			$this->request->redirect(Route::url('update', array('controller'=>'ad',
																'action'=>'update',
																'seotitle'=>$element->seotitle,
																'id'=>$element->id_ad)));
		}
	}

	/**
	 * [_image_path Get directory path of specific advert.]
	 * @param  [array] $data [all values of one advert.]
	 * @return [array]       [array of dir. path where images of advert. are ]
	 */
	public function _image_path($data)
	{
		$obj_ad = new Model_Ad();
		$directory = $obj_ad->_gen_img_path($data->seotitle, $data->created);

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

	public function action_image_delete()
	{
		// $this->auto_render = FALSE;
		// $this->template = View::factory('js');
		// echo $this->request->param('imgpath');
	}
	
}// End ad controller

