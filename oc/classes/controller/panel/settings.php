<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller SETTINGS contains all basic configurations displayed to Admin.
 */


class Controller_Panel_Settings extends Auth_Controller {


    /**
     * Contains all data releated to new advertisment optional form inputs,
     * captcha, uploading text file  
     * @return [view] Renders view with form inputs
     */
	public function action_form()
    {
        // validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

        // all form config values
        $advertisement = new Model_Config();
        $config = $advertisement->where('group_name', '=', 'advertisement')->find_all();

       

        // save only changed values
        if($this->request->post())
        {
            foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 

                if($config_res !== $c->config_value)
                {
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

        $this->template->content = View::factory('oc-panel/pages/settings/form', array('config'=>$config));
    }


    /**
     * Email configuration 
     * @return [view] Renders view with form inputs
     */
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

    /**
     * All general configuration related with configuring site.
     * @return [view] Renders view with form inputs
     */
    public function action_general()
    {
    	// validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';
        $this->template->scripts['footer'][]= '/js/oc-panel/settings.js';

        // all form config values
        $generalconfig = new Model_Config();
        $config = $generalconfig->where('group_name', '=', 'general')->find_all();
        $config_img = $generalconfig->where('group_name', '=', 'image')->find_all();
        // save only changed values
        if($this->request->post())
        {
        	foreach ($config as $c) 
            {   
                if ($c->config_key !== 'ID-pay_to_go_on_top')
                { 
                    if($c->config_key !== 'ID-pay_to_go_on_feature')
                    {
                        $config_res = $this->request->post($c->config_key);
                        if($c->config_key == 'allowed_formats'){
                            //@TODO
                        } 
                        
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
                }
            }
            foreach ($config_img as $ci) {
                $config_res = $this->request->post($ci->config_key);
                if($config_res != $ci->config_value)
                {
                    $ci->config_value = $config_res;
                    try {
                        $ci->save();
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
            // Cache::instance()->delete_all();
            Alert::set(Alert::SUCCESS, __('Success, General Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'general')));
        }

        $this->template->content = View::factory('oc-panel/pages/settings/general', array('config'=>$config, 'config_img'=>$config_img));
    }

    /**
     * Payment deatails and paypal configuration can be configured here
     * @return [view] Renders view with form inputs
     */
    public function action_payment()
    {
    	// validation active 
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

        // all form config values
        $paymentconf = new Model_Config();
        $config = $paymentconf->where('group_name', '=', 'payment')->find_all();
        
        $paypal_currency = Paypal::get_currency(); // currencies limited by paypal


        // save only changed values
        if($this->request->post())
        {
        	foreach ($config as $c) 
            {
                $config_res = $this->request->post($c->config_key); 

                
                if($c->config_key == 'paypal_currency')
                {   
                    $config_res = $paypal_currency[core::post('paypal_currency')];
                }

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

        $this->template->content = View::factory('oc-panel/pages/settings/payment', array('config'          => $config,
                                                                                          'paypal_currency' => $paypal_currency));
    }
}//end of controller