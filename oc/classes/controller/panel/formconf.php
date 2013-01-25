<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Formconf extends Auth_Controller {

    public function action_index()
    {
        $config = Kohana::$config->load('form');
       
        if($this->request->post())
        {

            // validation active        
            $this->template->scripts['footer'][]= '/js/jqBootstrapValidation.js';

            
            // open config file
            $filename = "oc/config/form.php";
            $fhandle = fopen($filename, 'r');
            
            // string to be written (return array with config values)
            $content = fread($fhandle, filesize($filename));
            
            // field values
            foreach ($config as $c => $val) 
            { d($config[$c]);
                // echo $this->request->post($c);
                $post = $this->request->post(); 
                // d($this->request->post($c));
                // echo serialize($config->get($c));
                foreach ($val as $val => $v) 
                {  
                    if($v == 1) $v = 'TRUE'; else $v = 'FALSE';

                    // d($val."=>".$v);
                    if($this->request->post($c) == $c){
                    $content = str_replace("'".$val."'=>".$v, "'".$val."'=>".$this->request->post($c.$val), $content);
                    }
                    //d($config->options);
                    // if ($value == 'TRUE' || $value == 'FALSE' || $post == "submit")
                    // {
                    //     if($post != "submit")
                    //     {
                    //         //var_export($post);
                    //         //$content = str_replace($post."=>".$value, $post."=>".$value, subject) ;
                    //        //Kohana::kohana_config_writer->write('crated','2','form');
                    //     }
                        
                    // } 
                    // else Alert::set(Alert::ERROR, __('You made mistake at '.$post.'. HINT: You can only use TRUE/FALSE to activate or deactivate'));
                   
                }
                $count = 0; $count++;
                echo $c;
            }
            $fhandle = fopen($filename, 'w');
            fwrite($fhandle, $content);
            fclose($fhandle);   
           
                   
            //  $return .= "),\n);";
            

            // foreach ($post as $post => $value) 
            // {
            //     if($post != 'submit')
            //     {
                     
            //     }
            // }

            // fwrite($fp, $return);
            
            //$contents = fread($fp, filesize($filename));

            // d($contents);
            // fclose($fp);
        }
        
        $this->template->content = View::factory('oc-panel/pages/formconf', array('fields'=>$config));
    }

}