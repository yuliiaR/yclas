<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_User extends Api_Auth {


    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            if (is_numeric($id_user = $this->request->param('id')))
            {
                $user = new Model_User($id_user);
                if ($user->loaded() AND $user->status == Model_User::STATUS_ACTIVE)
                {
                    $res = $user->as_array();
                    $res['image']     = $user->get_profile_image();

                    //I do not want to return this fields...
                    $hidden_fields =  array('password','token','api_token',
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

                    //we could use this function but is less elegant for me and less readable
                    /*$this->_return_fields = array_filter($this->_return_fields, function($var) {
                                    return (!in_array($var,array('password','token','api_token',
                                            'hybridauth_provider_uid','token_created','token_expires')));
                                });*/

                    $this->rest_output($res);
                }
                else
                    $this->_error(__('User not found'),404);
            }
            else
                $this->_error(__('User not found'),404);
           
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
    }


} // END