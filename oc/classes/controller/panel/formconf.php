<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Formconf extends Auth_Controller {

    public function action_index()
    {
        if($this->request->post())
        {

            // validation active        
            $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

            $bla = $this->request->post('title');
            
        }
        
        $this->template->content = View::factory('oc-panel/pages/formconf');
    }

}