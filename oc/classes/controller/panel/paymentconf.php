<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Paymentconf extends Auth_Controller {

    public function action_index()
    {
    	// validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
        $this->template->scripts['footer'][]= 'js/pages/config.js'; 

        // all form config values
        $paymentconf = new Model_Config();
        $config = $paymentconf->where('group_name', '=', 'payment')->find_all();
      
        // save only changed values
        if($this->request->post())
        {
        	foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 
                
                if($config_res != $c->config_value)
                {
                    $c->config_value = $config_res;
                    try {
                        $c->save();
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
            
            Alert::set(Alert::SUCCESS, __('Success, General Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'paymentconf','action'=>'index')));
        }

        $this->template->content = View::factory('oc-panel/pages/paymentconf', array('config'=>$config));
    }
}