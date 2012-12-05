<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Home extends Auth_Controller {

    
	public function action_index()
	{
		$this->template->title = 'Panel home';
		$this->template->content = View::factory('oc-panel/home');
	}
    

	

}
