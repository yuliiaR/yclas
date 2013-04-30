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
			// $user = Auth::instance()->get_user();

			
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
							//Form::errors($content->errors);
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

}
