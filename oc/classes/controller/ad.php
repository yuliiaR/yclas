<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	
	/**
	 * Serp of ads
	 */
	public function action_index()
	{ 
		$this->template->bind('content', $content);
		$data = $this->action_list_logic();
		
	    $this->template->content = View::factory('pages/post/listing',$data);
 	}

	public function action_list_logic()
	{
		$ads = new Model_Ad();
		$ads->where('ad.status', '=', Model_Ad::STATUS_PUBLISHED);
		$ads->find_all();

		$res_count = $ads->count_all();
		
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
    	    $ads = $ads->order_by('created','desc')
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
			echo $a->has_images."<br/>";
			if ($a->has_images == 1)
			{
				echo $a->seotitle."<br/>";
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
		
		print_r($img_path);
		return array('ads'=>$ads,'pagination'=>$pagination, 'user'=>$user, 'img_path' => $img_path);
	}
	

	
	public function action_search()
	{
		echo strlen($this->request->param('search',NULL));
		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/post/search');	
	}


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
	
	/**
	 * 
	 * Edit advertisement: Update
	 */
	public function action_update()
	{

		//template header
		$this->template->title           	= __('Edit advertisement');
		$this->template->meta_description	= __('Edit advertisement');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		//$this->template->scripts['footer'][]= 'js/autogrow-textarea.js';
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';

		$form = ORM::factory('ad', $this->request->param('id'));
		$cat = new Model_Category();
		$cat = $cat->find_all();
		
		$loc = $location = new Model_Location();
		// $loc = $location->where('id_location','=',$form->id_location)->limit(1)->find();
		$loc = $loc->find_all();

		$path = $this->_image_path($form);
		
		$this->template->content = View::factory('pages/post/edit', array('ad'		=>$form, 
																		'location'	=>$loc, 
																		'category'	=>$cat,
																		'path'		=>$path));
		
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
								'user'			=> $user = new Model_User()
								); 

				//insert data
				if ($this->request->post('title') != $data['title'])
				{
					$seotitle = $form->gen_seo_title($data['title']);
					$form->seotitle = $seotitle;	
				}
				 
				$form->title 			= $data['title'];
				$form->id_location 		= $data['loc'];
				$form->id_category 		= $data['cat'];
				$form->description 		= $data['description'];
				$form->status 			= $data['status'];									// need to be 0, in production 
				$form->price 			= $data['price']; 								
				$form->adress 			= $data['address'];
				$form->phone			= $data['phone']; 
				
				$obj_img = new Controller_New($this->request,$this->response);

				// image upload
				$error_message = NULL;
	    		$filename = NULL;
	    		
	    		if (isset($_FILES['image1']) || isset($_FILES['image2']))
	        	{ 
	        		$img_files = array($_FILES['image1'], $_FILES['image2']);
	            	
	            	$filename = $obj_img->_save_image($img_files, $data['seotitle']);
	        	}
	        	if ( $filename !== TRUE)
		        {
		            $error_message = 'There was a problem while uploading the image.
		                Make sure it is uploaded and must be JPG/PNG/GIF file.';

		                echo $error_message;
		        } else $form->has_images = 1;

				try 
				{
					
					$form->save();
					Alert::set(Alert::SUCCESS, __('Success, item updated'));
					$this->request->redirect(Route::url('default',array('controller'=>'home','action'=>'index')));
				
				}
				catch (ORM_Validation_Exception $e)
				{
					Form::errors($content->errors);
				}
				catch (Exception $e)
				{
					throw new HTTP_Exception_500($e->getMessage());

				}
				
			}
			
			
			
			// if ($form->loaded())
			// {
			// 	$this->template->content = View::factory('oc-panel/pages/edit', array('ad'=>$form));
			// }
			// else
			// {
			// 	//throw 404
			// 	throw new HTTP_Exception_404();
			// }
		}

	}

	public function _image_path($data)
	{
		//print_r($data->created);
		$obj_date = date_parse($data->created); // convert date to array 
		
			$year = substr($obj_date['year'], -2); // take last 2 integers 
		
		// check for length, because original path is with 2 integers 
		if(strlen($obj_date['month']) != 2)
			$month = '0'.$obj_date['month'];
		else
			$month = $obj_date['month'];
		
		if(strlen($obj_date['day']) != 2)
			$day = '0'.$obj_date['day'];
		else
			$day = $obj_date['day'];

		$directory = 'upload/'.$year.'/'.$month.'/'.$day.'/'.$data->seotitle.'/';
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

