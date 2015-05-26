<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Listings extends Api_Auth {


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

                $ads = new Model_Ad();

                //make a search with q? param
                if (isset($this->_params['q']) AND strlen($this->_params['q']))
                {
                    $ads->where_open()
                        ->where('title', 'like', '%'.$this->_params['q'].'%')
                        ->or_where('description', 'like', '%'.$this->_params['q'].'%')
                        ->where_close();
                }

                //filter results by param, verify field exists and has a value and sort the results
                $ads->api_filter($this->_filter_params)->api_sort($this->_sort);

                //how many? used in header X-Total-Count
                $count = $ads->count_all();

                //pagination with headers
                $pagination = $ads->api_pagination( $this->_params, $count,
                                                    array(
                                                                'controller' => $this->request->controller(),
                                                                'action'     => $this->request->action(),
                                                                'version'    => 'v1',
                                                    ));

                $ads = $ads->cached()->find_all();

                //as array
                foreach ($ads as $ad)
                {
                    //@todo include the images!!
                    $output[$ad->id_ad] = $ad->as_array();
                }

                $this->rest_output($output,200,$count,($pagination!==FALSE)?$pagination:NULL);
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
                    $this->rest_output(array($ad->as_array()));
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
