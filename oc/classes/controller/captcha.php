<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Captcha extends Kohana_Controller {

	public function action_image()
	{

		$token = $this->request->param('id');
		
		$captcha = new captcha();
		die($captcha->image($token));
	}
}//enc od controller captcha