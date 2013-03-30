<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Auth extends Controller {
    
    /**
     * 
     * Check if we need to login the user or display the form, same form for normal user and admin
     */
	public function action_login()
	{	    
		
	    //if user loged in redirect home
	    if (Auth::instance()->logged_in())
	    {
	    	Auth::instance()->login_redirect();
	    }
	    //posting data so try to login
	    elseif ($this->request->post() AND CSRF::valid('login'))
	    {	        
            Auth::instance()->login($this->request->post('email'), 
            						$this->request->post('password'),
            						(bool) $this->request->post('remember'));
            
            //redirect index
            if (Auth::instance()->logged_in())
            {
            	//is an admin so redirect to the admin home
            	Auth::instance()->login_redirect();
            }
            else 
            {
                Form::set_errors(array(__('Wrong email or password')));
            }
	        
	    }
	    	    
	    //Login page
	    $this->template->title            = __('Login');	    
	    $this->template->content = View::factory('pages/auth/login');
	}
	
	/**
	 * 
	 * Logout user session
	 */
	public function action_logout()
	{
	    Auth::instance()->logout(TRUE);    
	    $this->request->redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')));
	
	}
	
	/**
	 * Sends an email with a link to change your password
	 * 
	 */
	public function action_forgot()
	{
		$this->template->content = View::factory('pages/auth/forgot');
		$this->template->content->msg = '';
		
		//if user loged in redirect home
		if (Auth::instance()->logged_in())
		{
			$this->request->redirect(Route::get('oc-panel')->uri());
		}
		//posting data so try to remember password
		elseif ($this->request->post('email') AND CSRF::valid('forgot'))
		{
			$email = $this->request->post('email');
			
			if (Valid::email($email,TRUE))
			{
				//check we have this email in the DB
				$user = new Model_User();
				$user = $user->where('email', '=', $email)
							->limit(1)
							->find();
				
				if ($user->loaded())
				{
					//regenerating the token, for security
					$user->create_token();
					
					//url to redirect after ql success, change the pass
					$url_change = Route::url('oc-panel',array('directory'  => 'user',
														  'controller' => 'profile', 
														  'action'     => 'changepass'),'http');					
				
					$ql = Auth::instance()->ql_encode($user->token,$url_change);
					
					$url_ql = Route::url('oc-panel',array('directory'  => 'user',
													  'controller' => 'auth', 
													  'action'     => 'ql',
													  'id'		=>$ql),'http');
					//@todo not hard coded
					$ret = Email::send($email,
										'neo22s@gmail.com',
										__('Remember password'),
										'<a href="'.$url_ql.'">click here</a>');
					
					if ($ret) $this->template->content->msg = __('Email with link sent');
				}
				else
				{
					Form::set_errors(array(__('User not in database')));
				}
				
			}
			else
			{
				Form::set_errors(array(__('Email required')));
			}
			
		}
		
		//template header
		$this->template->title            = __('Remember password');
		
			
	}
	
	/**
	 * Simple register for user
	 *
	 */
	public function action_register()
	{
		$this->template->content = View::factory('pages/auth/register');
		$this->template->content->msg = '';
		
		//if user loged in redirect home
		if (Auth::instance()->logged_in())
		{
			$this->request->redirect(Route::get('oc-panel')->uri());
		}
		//posting data so try to remember password
		elseif ($this->request->post('email') AND CSRF::valid('register'))
		{
			$email = $this->request->post('email');
				
			if (Valid::email($email,TRUE))
			{
				if ($this->request->post('password1')==$this->request->post('password2'))
				{
					//check we have this email in the DB
					$user = new Model_User();
					$user = $user->where('email', '=', $email)
							->limit(1)
							->find();
			
					if ($user->loaded())
					{
						Form::set_errors(array(__('User already exists')));
					}
					else
					{
						//create user
						$user->email 	= $email;
						$user->name		= $this->request->post('name');
						$user->status	= Model_User::STATUS_ACTIVE;
						$user->id_role	= 1;//normal user
						$user->password = $this->request->post('password1');
						$user->seoname 	= URL::title($this->request->post('name'), '-', FALSE);
						
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
						
						//login the user
						Auth::instance()->login($this->request->post('email'), $this->request->post('password1'));
						$this->request->redirect(Route::uri('profile'));
						
					}
		
				}
				else
				{
					Form::set_errors(array(__('Passwords do not match')));
				}
			}
			else
			{
				Form::set_errors(array(__('Email required')));
			}
				
		}
	
		//template header
		$this->template->title            = __('Register new user');
			
	}
	/**
	 *
	 * Quick login for users.
	 * Useful for confirmation emails, remember passwords etc...
	 */
	public function action_ql()
	{
		$ql = $this->request->param('id');
		
		$url = Auth::instance()->ql_login($ql);
		
		//not a url go to login!
		if ($url==FALSE)
		{
			$url = Route::url('oc-panel',array('controller' => 'auth', 
										  		'action'     => 'login'),'http');	
		}
		$this->request->redirect($url);
	}

}
