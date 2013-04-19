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
			
			if(core::config('advertisement.captcha') == FALSE || captcha::check('contact'))
			{ 
				
				$message = array('name'			=>core::post('name'),
								 'email_from'	=>core::post('email'),
								 'subject'		=>core::post('subject'),
								 'message'		=>core::post('message'),);
				


                    // //we get the QL, and force the regen of token for security
                    // $url_ql = $user->ql('oc-panel',array( 'controller' => 'profile', 
                    //                                       'action'     => 'changepass'),TRUE);
                    // $ret = $user->email('auth.remember',array('[URL.QL]'=>$url_ql));


				 email::send(core::config('email.notify_email'),$message['subject'],$message['message'],$message['email_from'],$message['name']);
				Alert::set(Alert::SUCCESS, __('Success, your message is sent'));

				
			}
			else
			{
				Alert::set(Alert::ERROR, __('Check the form for errors'));
			}
			
		}
	
	    
		$this->template->content = View::factory('pages/contact', array());
	}

}