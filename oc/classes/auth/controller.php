<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Front end controller for OC user/admin auth in the app
 * Also contains basic CRUD actions for the
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2011 Open Classifieds Team
 * @license    GPL v3
 */

class Auth_Controller extends Controller
{
	/**
	 * role to access this controller by default 1 = user
	 */
	public $role =  Model_User::ROLE_USER;
	 
	/**
	 *
	 * Contruct that checks you are loged in before nothing else happens!
	 */
	function __construct(Request $request, Response $response)
	{
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;


		//login control, don't do it for auth controller so we dont loop
		if ($this->request->controller()!='auth')
		{
			//home url used in the breadcrumb
			//Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
			
			//depending on the directory that you are trying to access some role or another...
			switch ($this->request->directory())
			{
				case 'admin':
					$this->role = Model_User::ROLE_ADMIN;				
					//admin url used in the breadcrumb
					$url_bread = Route::url('user',array('directory'  => $this->request->directory(),
														'controller'  => 'home'));
					Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Administration'))->set_url($url_bread));
					break;
				case 'user':
					$this->role = Model_User::ROLE_USER;
					//User url used in the breadcrumb
					$url_bread = Route::url('user',array('directory'  => $this->request->directory(),
														'controller'  => 'profile','action'  => 'index'));
					Breadcrumbs::add(Breadcrumb::factory()->set_title(__('User'))->set_url($url_bread));
					
					break;
			}

			//check if user is login
			if (!Auth::instance()->logged_in($this->role))
			{
				$url = Route::get('user')->uri(array('directory'  => 'user',
													 'controller' => 'auth', 
													 'action'     => 'login'));
				$this->request->redirect($url);
			}

		}

		//the user was loged in and with the right permissions
		
		
	}

	/**
	 * Initialize properties before running the controller methods (actions),
	 * so they are available to our action.
	 */
	public function before()
	{
		//only for the admin different template
		if ($this->role ==  Model_User::ROLE_ADMIN)
		{
			if($this->auto_render===TRUE)
			{
				// Load the template
				$this->template = 'admin/main';
				$this->template = View::factory($this->template);
					
				// Initialize empty values
				$this->template->title            = 'Admin';
				$this->template->meta_keywords    = '';
				$this->template->meta_description = '';
				$this->template->meta_copywrite   = 'Open Classifieds '.Core::version;
				$this->template->header           = View::factory('admin/header');
				$this->template->content          = '';
				$this->template->footer           = View::factory('admin/footer');
				$this->template->styles           = array();
				$this->template->scripts          = array();
				View::$styles	        		  = array('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css' => 'screen');
				View::$scripts['header']	= array('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js',	
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js'
									);
			}
		}
		//using default
		else 
		{
			parent::before();
		}
	}


}