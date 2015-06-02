<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_Profile extends Api_User {


    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        $url = Route::url('api',array('controller'=>'user',
                                                'action'  => 'index',
                                                'id'=>$this->request->param('id')));

        $this->_error($url,404);
    }



    public function action_update()
    {

    }

    public function action_picture()
    {
        
    }

} // END