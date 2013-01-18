<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	
		/**
	 * Serp of ads
	 */
	public function action_index()
	{ 
		$this->template->bind('content', $content);
	    $this->template->content = View::factory('pages/post/listing',$this->action_list_logic());
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
		return array('ads'=>$ads,'pagination'=>$pagination, 'user'=>$user);
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
		$form = ORM::factory('ad', $this->request->param('id'));
		$cat = new Model_Category();
		$cat = $cat->find_all();
		
		$loc = $location = new Model_Location();
		// $loc = $location->where('id_location','=',$form->id_location)->limit(1)->find();
		$loc = $loc->find_all();

		$path = $this->_image_path($form->created);
		
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
								'address'		=> $address 	= 	$this->request->post('address'),
								'phone'			=> $phone 		= 	$this->request->post('phone'),
								'user'			=> $user = new Model_User()
								); 

				//insert data
				$data['seotitle'] = $data['title'].$form->count_all(); // bad solution, find better !!! 

				$form->title 			= $data['title'];
				$form->id_location 		= $data['loc'];
				$form->id_category 		= $data['cat'];
				$form->description 		= $data['description'];
				$form->seotitle 		= $data['seotitle'];	 
				// $form->status 			= $status;									// need to be 0, in production 
				$form->price 			= $data['price']; 								
				$form->adress 			= $data['address'];
				$form->phone			= $data['phone']; 
			try {
				$form->save();
				Alert::set(Alert::SUCCESS, __('Success, item updated'));

				$this->request->redirect(Route::url('default',array('controller'=>'home','action'=>'index')));
			} catch (Exception $e) {
				echo $e;
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
		//echo $data.PHP_EOL;
		echo $my_date = date('y/m/d', strtotime($data));
		return $path = array("/upload/13/01/18/IMAGE12/123.jpg","/upload/13/01/18/IMAGE12/321.jpg");
	}
	
}// End ad controller

