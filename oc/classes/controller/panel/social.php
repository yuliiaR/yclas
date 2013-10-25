<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Social extends Auth_Controller {

    
    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Social Auth'))->set_url(Route::url('oc-panel',array('controller'  => 'social'))));

    }

	public function action_index()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Social Authentication for login')));
		$this->template->title = __('Social Auth');

        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';

        $config = json_decode(core::config('social.config'));
        //retrieve social_auth

		$this->template->content = View::factory('oc-panel/pages/social_auth/index',array('config'=>$config));
	}
}