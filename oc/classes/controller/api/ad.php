<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Ad extends Api_User {


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
                $ads = new Model_Ad();
                $ads = $ads->limit(10)->find_all();

                foreach ($ads as $ad) 
                {
                    $output[$ad->id_ad] = $ad->as_array();
                }

                $this->rest_output($output);
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
    }

    public function action_get()
    {
        try
        {
            $result = array();
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad($id_ad);
                if ($ad->loaded())
                {
                    $result = array($ad->as_array());
                }
            }
           

            $this->rest_output($result);
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
            $this->rest_output( 'ok'.print_r( Core::request('description2'),1 ));
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
       
    }


} // END
