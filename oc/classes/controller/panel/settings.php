<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings extends Auth_Controller {

	 public function action_form()
    {
        // validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

        // all form config values
        $formconfig = new Model_Config();
        $config = $formconfig->where('group_name', '=', 'formconfig')->find_all();

        // form config name
        $form_name = array();
        foreach ($config as $c) 
        {
            $form = strchr($c->config_key, '-', true);
            
            if(!in_array($form, $form_name))
            {
                array_push($form_name, $form);    
            }
            
        }

        // save only changed values
        if($this->request->post())
        {
            foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 
                
                if($config_res !== $c->config_value)
                {echo $config_res." ".$c->config_value;
                    $c->config_value = $config_res;
                    try {
                        $c->save();
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'form')));
            
        }

        $this->template->content = View::factory('oc-panel/pages/settings/form', array('form_name'   =>$form_name,
                                                                                  	   'config'      =>$config));
    }

    public function action_email()
    {
    	// validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js'; 

        // all form config values
        $emailconf = new Model_Config();
        $config = $emailconf->where('group_name', '=', 'email-settings')->find_all();

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
            // Cache::instance()->delete_all();
            Alert::set(Alert::SUCCESS, __('Success, Email Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'email')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/email', array('config'=>$config));
    }

    public function action_general()
    {
    	// validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

        // all form config values
        $generalconfig = new Model_Config();
        $config = $generalconfig->where('group_name', '=', 'general')->find_all();
      
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
            // Cache::instance()->delete_all();
            Alert::set(Alert::SUCCESS, __('Success, General Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'general')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/general', array('config'=>$config));
    }

    public function action_payment()
    {
    	// validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

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
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'payment')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/payment', array('config'=>$config));
    }
}//end of controller