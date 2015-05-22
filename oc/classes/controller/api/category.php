<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Category extends Api_Controller {


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
                $this->rest_output(Model_Category::get_as_array());
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
            if (is_numeric($id_category = $this->request->param('id')))
            {
                $category = new Model_Category($id_category);
                if ($category->loaded())
                {
                    $result = array(
                                    'category' => $category->as_array(),
                                    'parents'  => $category->get_parents_ids(),
                                    'siblings' => $category->get_siblings_ids(),
                                    );
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

} // END