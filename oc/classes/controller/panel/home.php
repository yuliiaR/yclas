<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Home extends Auth_Controller {

    
	public function action_index()
	{
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Welcome')));
		$this->template->title = 'Welcome';
		$this->template->content = View::factory('oc-panel/home');
	}
    

	

}
