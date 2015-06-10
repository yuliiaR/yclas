<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_Users extends Api_Auth {


    //I do not want to return this fields...
     private    $_hidden_fields =  array('password','token','api_token',
                                            'hybridauth_provider_uid','token_created','token_expires',
                                            'user_agent');

    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $this->action_get();
            }
            else
            {
                $output = array();

                $users = new Model_User();

                $users->where('status', '=', Model_User::STATUS_ACTIVE);

                //filter results by param, verify field exists and has a value and sort the results
                $users->api_filter($this->_filter_params)->api_sort($this->_sort);

                //how many? used in header X-Total-Count
                $count = $users->count_all();

                //pagination with headers
                $pagination = $users->api_pagination($count,$this->_params['items_per_page']);

                $users = $users->cached()->find_all();

                //as array
                foreach ($users as $user)
                {
                    $u = $user->as_array();
                    $u['image'] = $user->get_profile_image();

                    //remove the hidden fields
                    foreach ($u as $key => $value) 
                    {
                        if(in_array($key,$this->_hidden_fields))
                            unset($u[$key]);
                    }

                    $output[] = $u;
                }

                $this->rest_output(array('users' => $output),200,$count,($pagination!==FALSE)?$pagination:NULL);
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
    }

    /**
     * Handle GET requests.
     */
    public function action_get()
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

                    //remove the hidden fields
                    foreach ($res as $key => $value) 
                    {
                        if(in_array($key,$this->_hidden_fields))
                            unset($res[$key]);
                    }

                    $this->rest_output(array('user' => $res));
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