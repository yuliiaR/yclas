<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Profile extends Auth_Controller {


	public function action_index()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(ucfirst(__('Home'))));
		
		$this->template->title = __('User home');
		$this->template->scripts['footer'][] = 'js/user/index.js';
		$this->template->content = View::factory('pages/user/home');
	}


	public function action_changepass()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(ucfirst(__('Change password'))));
		
		$this->template->title   = __('Change password');
		$this->template->content = View::factory('pages/user/profile/changepass');
		$this->template->content->msg ='';

		if ($this->request->post() AND CSRF::valid())
		{
			if ($this->request->post('password1')==$this->request->post('password2'))
			{
				$user = Auth::instance()->get_user();
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
				
				$this->template->content->msg =__('Password changed');
			}
			else
			{
				Form::set_errors(array(__('Passwords do not match')));
			}
		}

	  
	}




}
