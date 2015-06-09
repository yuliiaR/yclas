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
                $res = $user->as_array();
                $res['image']     = $user->get_profile_image();

                //I do not want to return this fields...
                $hidden_fields =  array('password','token',
                                        'hybridauth_provider_uid','token_created','token_expires',
                                        'user_agent');

                //all fields
                $this->_return_fields = array_keys($user->table_columns());

                //remove the hidden fields
                foreach ($this->_return_fields as $key => $value) 
                {
                    if(in_array($value,$hidden_fields))
                        unset($this->_return_fields[$key]);
                }

                $this->rest_output($res);
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