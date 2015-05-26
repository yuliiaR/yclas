<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_Auth extends Api_Auth {


    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        // If the passwords match, perform a login
        if ( ($user = Auth::instance()->email_login(Core::request('email'), Core::request('password'))) !== FALSE)
        {
            if ($user->loaded())
            {
                $this->rest_output(array('user_token'=>$user->api_token()));
            }
        }
        else
            $this->_error(__('Wrong user name or password'),401);
    }



    public function action_create()
    {
        $this->action_index();
    }

} // END