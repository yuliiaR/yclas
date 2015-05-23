<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Ads extends Api_User {


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

                //return only requested fields, verify field exists
               /* if (is_array($this->_return_fields))
                {
                    $select = array();
                    foreach ($this->_return_fields as $key => $field) 
                    {
                        if(in_array($field,(array_keys($ads->table_columns()))))
                            $select[]= $field;
                    }
                    $ads->select($select);
                }*/

                //sorting results by param, verify field exists and has a value
                foreach ($this->_params as $field => $value) 
                {
                    if(in_array($field,(array_keys($ads->table_columns()))) AND isset($value) AND !empty($value))
                        $ads->where($field,'=',$value);
                }

                //sorting results by param, verify field exists
                foreach ($this->_sort as $field => $direction) 
                {
                    if(in_array($field,(array_keys($ads->table_columns()))))
                        $ads->order_by($field,$direction);
                }

                $count = $ads->count_all();

                //d($count);//TODO header!!!

                //amount per page
                $ads->limit((isset($this->_params['limit'])?$this->_params['limit']:10));

                //page X
                if (isset($this->_params['offset']) AND is_numeric($this->_params['offset']))
                {
                    $ads->offset($this->_params['offset']);
                }
                                
                $ads = $ads->cached()->find_all();

                //as array
                foreach ($ads as $ad) 
                    $output[$ad->id_ad] = $ad->as_array();

                $this->rest_output($output,200,$count);
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
