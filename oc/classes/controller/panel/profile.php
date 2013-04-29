<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Profile extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('User'))->set_url(Route::url('oc-panel',array('controller'  => 'profile'))));
    }

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




}
