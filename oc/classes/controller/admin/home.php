<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Home extends Auth_Controller {

    
	public function action_index()
	{
		$this->template->title = 'Admin home';
		$this->template->content = View::factory('admin/home');
	}
    

	
	
	

}
