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
			
			$url_bread = Route::url('oc-panel',array('controller'  => 'home'));
			Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Panel'))->set_url($url_bread));
				
			//check if user is login
			if (!Auth::instance()->logged_in($this->role))
			{
				$url = Route::get('oc-panel')->uri(array(
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
	
		if($this->auto_render===TRUE)
		{
			// Load the template
			$this->template = 'oc-panel/main';
			$this->template = View::factory($this->template);
				
			// Initialize empty values
			$this->template->title            = 'Panel';
			$this->template->meta_keywords    = '';
			$this->template->meta_description = '';
			$this->template->meta_copywrite   = 'Open Classifieds '.Core::version;
			$this->template->header           = View::factory('oc-panel/header');
			$this->template->content          = '';
			$this->template->footer           = View::factory('oc-panel/footer');
			$this->template->styles           = array();
			$this->template->scripts          = array();
			View::$styles	        		  = array('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css' => 'screen');
			View::$scripts['header']	= array('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js',	
								'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js'
								);
		}
		
		
	}


}