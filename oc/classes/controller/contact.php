<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contact extends Controller {

	public function action_index()
	{ 

		//template header
		$this->template->title           	= __('Contact Us');
		$this->template->meta_description	= __('Contact Us');
				
		$this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
		$this->template->scripts['footer'][]= 'js/pages/new.js';

		

		if($this->request->post()) //message submition  
		{
			$user = new Model_User();
			$user = $user->where('email', '=', core::post('email'))
						->limit(1)
						->find();
			
			if($user->loaded())
			{			
				if(core::config('advertisement.captcha') == FALSE || captcha::check('contact'))
				{ 
					$ret = $user->email('contact.us',array('[EMAIL.BODY]'		=>core::post('message'),
                    									   '[EMAIL.SENDER]'		=>core::post('name'),
                    									   '[EMAIL.FROM]'		=>core::post('email_from')));
					Alert::set(Alert::SUCCESS, __('Success, your message is sent'));
				}
				else
				{
					Alert::set(Alert::ERROR, __('Check the form for errors'));
				}
			}	
		}

		// we ensure that user is logged in to avoid spam
		$user_auth = (!Auth::instance()->logged_in()) ? $this->template->content = View::factory('pages/contact', array('user_auth'=>FALSE)) : $this->template->content = View::factory('pages/contact', array('user_auth'=>TRUE));
		
	}

	//email message generating, for single ad. Client -> owner  
	public function action_user_contact()
	{	
		$ad = new Model_Ad($this->request->param('id'));
		$user_id = $ad->id_user;

		$user = new Model_User($user_id);

		$email_content = Model_Content::get('user.contact','email');

		//message to user
		if($user)
		{
			if(captcha::check('contact'))
			{ 
				Alert::set(Alert::SUCCESS, __('Success, your message is sent'));
                $ret = $user->email('user.contact',array('[EMAIL.BODY]'		=>core::post('message'),
                    									 '[EMAIL.SENDER]'	=>core::post('name'),
                    									 '[EMAIL.FROM]'		=>core::post('email_from')));
			}
			else
			{
				Alert::set(Alert::ERROR, __('You made some mistake'));
			}
		}
	
	}

	public function action_ad_activated()
	{
		$admin = Auth::instance()->get_user()->id_user;
		$user = new Model_User($admin);

		$category = new Model_Category($active_ad->id_category);

		if($user)
		{
			//we get the QL, and force the regen of token for security
        	$url_ql = $user->ql('ad',array( 'category' => $category->seoname, 
                                            'seotitle'=> $active_ad->seotitle),TRUE);

        	$ret = $user->email('ads.activated',array('[URL.QL]'=>$url_ql));
		}

			
	} 

}