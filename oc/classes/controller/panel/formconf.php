<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Formconf extends Auth_Controller {

    public function action_index()
    {
        $config = Kohana::$config->load('form');
        // validation active        
        $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

        if($this->request->post())
        {
            // open config file
            $filename = "oc/config/form.php";
            $fhandle = fopen($filename, 'r');
            
            // string to be written (return array with config values)
            $content = fread($fhandle, filesize($filename));
            
            // field values
            foreach ($config as $c => $val) 
            {
               // $post = $this->request->post(); 
              
                foreach ($val as $val => $v) 
                {   
                    if($v == 1) $v = 'TRUE'; else $v = 'FALSE';
                    if($this->request->post($val) == 0) $input = "TRUE"; else $input = "FALSE";
                    $content = str_replace("'".$val."'=>".$v, "'".$val."'=>".$input, $content);
                }
            }

            $fhandle = fopen($filename, 'w');
            fwrite($fhandle, $content);
            fclose($fhandle);   
            
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'formconf','action'=>'index')));
                   
        }
        
        $this->template->content = View::factory('oc-panel/pages/formconf', array('fields'=>$config));
    }

}