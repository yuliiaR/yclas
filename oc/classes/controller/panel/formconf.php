<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Formconf extends Auth_Controller {

    public function action_index()
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
                
                if($config_res == 0) $config_res = 'TRUE'; else $config_res = 'FALSE';
                
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
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'formconf','action'=>'index')));
            
        }

        $this->template->content = View::factory('oc-panel/pages/formconf', array('form_name'   =>$form_name,
                                                                                  'config'      =>$config));





    //     $config = new Formconfig($this->request, $this->response);
    //     $config = $config->form();
    //     // validation active        
    //     $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

    //     if($this->request->post())
    //     {
    //         // open config file
    //         $filename = "oc/classes/formconfig.php";
    //         $fhandle = fopen($filename, 'r');
            
    //         // string to be written (return array with config values)
    //         $content = fread($fhandle, filesize($filename));
            
    //         // field values
    //         foreach ($config as $c => $val) 
    //         {
    //            // $post = $this->request->post(); 
              
    //             foreach ($val as $val => $v) 
    //             {   
    //                 if($v == 1) $v = 'TRUE'; else $v = 'FALSE';
    //                 if($this->request->post($val) == 0) $input = "TRUE"; else $input = "FALSE";
    //                 $content = str_replace("'".$val."'=>".$v, "'".$val."'=>".$input, $content);
    //             }
    //         }

    //         $fhandle = fopen($filename, 'w');
    //         fwrite($fhandle, $content);
    //         fclose($fhandle);   
            
    //         $this->request->redirect(Route::url('oc-panel',array('controller'=>'formconf','action'=>'index')));
                   
    //     }
        
    //     $this->template->content = View::factory('oc-panel/pages/formconf', array('fields'=>$config));
    }

}