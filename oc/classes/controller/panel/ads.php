<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Ads extends Auth_Controller {

   
	public function action_index()
	{
 		$c = new Controller_Post($this->request,$this->response);// object of listing
	    $this->template->content = View::factory('oc-panel/ads', $c->action_list_logic()); // create view, and insert list with data 		
	}

}
