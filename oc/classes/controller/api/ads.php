<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Ads extends Api_User {




    public function action_get()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad($id_ad);
                if ($ad->loaded())
                {
                    if ($ad->id_user == $this->user->id_user)
                    {
                        $this->rest_output(array($ad->as_array()));
                    }
                    else
                        $this->_error(__('Not your advertisement'),401);
                }
                else
                    $this->_error(__('Advertisement not found'),404);
            }
            else
                $this->_error(__('Advertisement not found'),404);
            
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
       
    }


    /**
     * Handle PUT requests.
     */
    public function action_update()
    {
        try
        {
            $result = array();
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad($id_ad);
                if ($ad->loaded())
                {
                    if ($ad->id_user == $this->user->id_user)
                    {
                        $this->rest_output($this->_params);
                    }
                    else
                        $this->_error(__('Not your advertisement'),401);
                }
                else
                    $this->_error(__('Advertisement not found'),404);
            }
            else
                $this->_error(__('Advertisement not found'),404);

        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
       
    }


} // END
