<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Ad extends Auth_Controller {

   	/**
   	 * List all Advertisements (PUBLISHED)
   	 */
	public function action_index()
	{
		//template header
		$this->template->title           	= __('Advertisements');
		$this->template->meta_description	= __('Advertisements');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= 'js/oc-panel/moderation.js';

		//find all tables 
        $hits = new Model_Visit();
        $hits->find_all();

		$cat = new Model_Category();
		$_list_cat= $cat->find_all(); // get all to print at sidebar view
		
		$loc = new Model_Location();
		$_list_loc= $loc->find_all(); // get all to print at sidebar view

		$c = new Controller_Ad($this->request,$this->response); // object of listing
        
        $arr_ads = $c->list_logic(); 
       	$arr_hits = array(); // array of hit integers 
       	
        // fill array with hit integers 
        foreach ($arr_ads['ads'] as $key_ads) {
        	
        	// match hits with ad
        	$hits->where('id_ad','=', $key_ads->id_ad)->and_where('id_user', '=', $key_ads->id_user);
        	$count = $hits->count_all(); // count individual hits 

        	array_push($arr_hits, $count);
        }
        

	    $this->template->content = View::factory('oc-panel/pages/ad',array('res'			=>$arr_ads, 
	    																	'hits'			=>$arr_hits, 
	    																	'category'		=>$_list_cat,
	    																	// 'captcha_show'	=>$captcha_show,
	    																	'location'		=>$_list_loc,
	    																	)); // create view, and insert list with data 		
	}

	/**
	 * Action MODERATION
	 */
	
	public function action_moderate()
	{
		//template header
		$this->template->title           	= __('Moderation');
		$this->template->meta_description	= __('Moderation');
				
		$this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		//$this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['footer'][]= 'js/oc-panel/moderation.js'; 


		//find all tables 
		
		$ads = new Model_Ad();

		$res_count = $ads->where('ad.status', '!=', Model_Ad::STATUS_PUBLISHED)->count_all();
		
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
    	    $ads = $ads->where('ad.status', '!=', Model_Ad::STATUS_PUBLISHED)
    	    					->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();
		
	        //find all tables 
	        $hits = new Model_Visit();
	        $hits->find_all();

			$cat = new Model_Category();
			$_list_cat = $cat->find_all(); // get all to print at sidebar view
			
			$loc = new Model_Location();
			$_list_loc = $loc->find_all(); // get all to print at sidebar view


	       	$arr_hits = array(); // array of hit integers 
	       	
	        // fill array with hit integers 
	        foreach ($ads as $key_ads) {
	        	
	        	// match hits with ad
	        	$hits->where('id_ad','=', $key_ads->id_ad)->and_where('id_user', '=', $key_ads->id_user);
	        	$count = $hits->count_all(); // count individual hits 

	        	array_push($arr_hits, $count);
	        }

			$this->template->content = View::factory('oc-panel/pages/moderate',array('ads'			=> $ads,
																					'pagination'	=> $pagination,
																					'category'		=> $_list_cat,
																					'location'		=> $_list_loc,
																					'hits'			=> $arr_hits)); // create view, and insert list with data

		}
		else
		{
			Alert::set(Alert::INFO, __('You do not have any not published advertisemet'));
			$this->template->content = View::factory('oc-panel/pages/moderate', array('ads' => NULL));
		}
        
	} 

	/**
	 * Delete advertisement: Delete
	 */
	public function action_delete()
	{
		$element = ORM::factory('ad', $this->request->param('id'));
		$id = $this->request->param('id');
		
		$format_id = explode('_', $id);

		if(Auth::instance()->logged_in() && Auth::instance()->get_user()->id_user == $element->id_user 
			|| Auth::instance()->logged_in() && Auth::instance()->get_user()->id_role == 10)
		{
			foreach ($format_id as $id) 
			{
				
				if ($id !== '')
				{
					$this->auto_render = FALSE;
					$this->template = View::factory('js');
					$element = ORM::factory('ad', $id);
					
					try
					{
						
						$img_path = $element->gen_img_path($element->id_ad, $element->created);
						

						if (!is_dir($img_path)) 
						{
							$element->delete();
							
						}
						else
						{
							// Loop through the folder
							$dir = dir($img_path);

							while (false !== $entry = $dir->read()) {
							// Skip pointers
								if ($entry == '.' || $entry == '..') {
									continue;
								}
								unlink($img_path.$entry);
							}
							
							rmdir($img_path);
							$element->delete();
						}
					}
					catch (Exception $e)
					{
						Alert::set(Alert::ALERT, __('Warning, something went wrong while deleting'));
						throw new HTTP_Exception_500($e->getMessage());
					}	
				}
				
			}
			Alert::set(Alert::SUCCESS, __('Success, advertisemet is deleted'));
			Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		}
		else
		{
			Alert::set(Alert::ERROR, __('You dont have permission to access this link'));
			$this->request->redirect(Route::url('default'));
		}
	}

	/**
	 * Mark advertisement as spam : STATUS = 30
	 */
	public function action_spam()
	{
		$id = $this->request->param('id');
		
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{ 
			if ($id !== '')
			{ 
				$spam_ad = ORM::factory('ad', $id);

				if ($spam_ad->loaded())
				{
					if ($spam_ad->status != 30)
					{
						$spam_ad->status = 30;
						
						try
						{
							$spam_ad->save();
						}
						catch (Exception $e)
						{
							throw new HTTP_Exception_500($e->getMessage());
						}
					}
					else
					{				
						Alert::set(Alert::ALERT, __('Warning, advertisemet is already marked as spam'));
						Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
					} 
				}
				else
				{
					//throw 404
					throw new HTTP_Exception_404();
				}
			}
		}
		Alert::set(Alert::SUCCESS, __('Success, advertisemet is marked as spam'));
		Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		
	}

	/**
	 * Mark advertisement as deactivated : STATUS = 50
	 */
	public function action_deactivate()
	{

		$id = $this->request->param('id');
		
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{
			if ($id !== '')
			{

				$deact_ad = ORM::factory('ad', $id);

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
						Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
					} 
				}
				else
				{
					//throw 404
					throw new HTTP_Exception_404();
				}
			}
		}
		Alert::set(Alert::SUCCESS, __('Success, advertisemet is deactivated'));
		Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
	}

	/**
	 * Mark advertisement as active : STATUS = 1
	 */
	
	public function action_activate()
	{

		$id = $this->request->param('id');
		
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{
			if ($id !== '')
			{
				$active_ad = ORM::factory('ad', $id);

				if ($active_ad->loaded())
				{
					if ($active_ad->status != 1)
					{
						$active_ad->published = Date::unix2mysql(time());
						$active_ad->status = 1;
						// $active_ad->published = 
						
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
						Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
					} 
				}
				else
				{
					//throw 404
					throw new HTTP_Exception_404();
				}
			}
		}

		if (Core::config('sitemap.on_post') == TRUE)
			Sitemap::generate();

		Alert::set(Alert::SUCCESS, __('Success, advertisemet is active and published'));
		Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
	}

	public function action_featured()
	{

		$id = $this->request->param('id');
	
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{
			if ($id !== '')
			{
				$featured_ad = ORM::factory('ad', $id);

				if ($featured_ad->loaded())
				{
					
					if($featured_ad->featured == NULL)
					{
						$featured_ad->featured = Date::unix2mysql(time() + (core::config('general.featured_timer') * 24 * 60 * 60));
	        
				        try {
				            $featured_ad->save();
				        } catch (Exception $e) {
				 	          
				        }
				    }
				    else
					{				
						$featured_ad->featured = NULL;
						try {
				            $featured_ad->save();
				        } catch (Exception $e) {
				 	          
				        }
					} 
			    }
			    else
				{
					//throw 404
					throw new HTTP_Exception_404();
				}
			    
			}
		}
		Alert::set(Alert::SUCCESS, __('Success, advertisemet is featured'));
		Request::current()->redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));

	}


}
