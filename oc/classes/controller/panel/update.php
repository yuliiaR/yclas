<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Update controllers 
 *
 * @package    OC
 * @category   Update
 * @author     Chema <chema@garridodiaz.com>, Slobodan <slobodan.josifovic@gmail.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Update extends Auth_Controller {    


    public function action_index()
    {
        
        //force update check reload
        if (Core::get('reload')==1 )
            Core::get_updates(TRUE);
        
        $versions = core::config('versions');

        if (Core::get('json')==1)
        {
            $this->auto_render = FALSE;
            $this->template = View::factory('js');
            $this->template->content = json_encode($versions);  
        }
        else
        {
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Updates')));
            $this->template->title = __('Updates');
        
            //check if we have latest version of OC
            if (key($versions)!=core::version)
                Alert::set(Alert::ALERT,__('You are not using latest version of OC, please update.'));
            

            //pass to view from local versions.php         
            $this->template->content = View::factory('oc-panel/pages/tools/versions',array('versions'       =>$versions,
                                                                                           'latest_version' =>key($versions)));
        }        

    }

    /**
     * This function will upgrate configs that didn't existed in verisons below 2.0.3 
     */
    public function action_203()
    {
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'watermark',
                               'group_name'     =>'image', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'watermark_path',
                               'group_name'     =>'image', 
                               'config_value'   =>''), 
                         array('config_key'     =>'watermark_position',
                               'group_name'     =>'image', 
                               'config_value'   =>'0'),
                         array('config_key'     =>'ads_in_home',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'0'));
        
        $contents = array(array('order'=>'0',
                               'title'=>'Hello [USER.NAME]!',
                               'seotitle'=>'userprofile.contact',
                               'description'=>"User [EMAIL.SENDER] [EMAIL.FROM], have a message for you: \n\n [EMAIL.SUBJECT] \n\n[EMAIL.BODY]. \n\n Regards!",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'));
        
        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);

        // message
        if($return_conf OR $return_cont)
            Alert::set(Alert::SUCCESS,__('Updated'));
        else
            Alert::set(Alert::INFO,__('Nothing to Update'));

        //$this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index')));
    }

    /**
     * This function will upgrate DB that didn't existed in verisons below 2.0.5 
     * changes added: subscription widget, new email content, map zoom, paypal seller etc..  
     */
    public function action_205()
    {
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'paypal_seller',
                               'group_name'     =>'payment', 
                               'config_value'   =>'0'),
                         array('config_key'     =>'map_zoom',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>'16'),
                         array('config_key'     =>'center_lon',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>'3'),
                         array('config_key'     =>'center_lat',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>'40'),
                         array('config_key'     =>'new_ad_notify',
                               'group_name'     =>'email', 
                               'config_value'   =>'0'));

        $contents = array(array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is created on [SITE.NAME]!',
                               'seotitle'=>'ads.subscribers',
                               'description'=>"Hello [USER.NAME],\n\nYou may be interested in this one [AD.TITLE]!\n\nYou can visit this link to see advertisement [URL.AD]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is created on [SITE.NAME]!',
                               'seotitle'=>'ads.to_admin',
                               'description'=>"Click here to visit [URL.AD]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'));

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);

        $prefix = Database::instance()->table_prefix();
        $config_db = Kohana::$config->load('database');
        $charset = $config_db['default']['charset'];
        
        /*
          @todo NOT DINAMIC, get charset
        */
        mysql_query("CREATE TABLE IF NOT EXISTS `".$prefix."subscribers` (
                    `id_subscribe` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_user` int(10) unsigned NOT NULL,
                    `id_category` int(10) unsigned NOT NULL DEFAULT '0',
                    `id_location` int(10) unsigned NOT NULL DEFAULT '0',
                    `min_price` decimal(14,3) NOT NULL DEFAULT '0',
                    `max_price` decimal(14,3) NOT NULL DEFAULT '0',
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id_subscribe`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=".$charset.";");
        
        // remove INDEX from content table
        mysql_query("ALTER TABLE `".$prefix."content` DROP INDEX `".$prefix."content_UK_seotitle`");
        
        // message
        if($return_conf OR $return_cont)
            Alert::set(Alert::SUCCESS,__('Updated'));
        else
            Alert::set(Alert::INFO,__('Nothing to Update'));

        //$this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index')));
    }

    /**
     * This function will upgrate DB that didn't existed in verisons below 2.0.5 
     * changes added: config for landing page, etc..  
     */
    public function action_206()
    {
      // build array with new (missing) configs
        $configs = array(array('config_key'     =>'landing_page',
                               'group_name'     =>'general', 
                               'config_value'   =>'{"controller":"home","action":"index"}'),
                         array('config_key'     =>'banned_words',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>''),
                         array('config_key'     =>'banned_words_replacement',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>''),
                         array('config_key'     =>'akismet_key',
                               'group_name'     =>'general', 
                               'config_value'   =>''));

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);

        // message
        if($return_conf)
            Alert::set(Alert::SUCCESS,__('Updated'));
        else
            Alert::set(Alert::INFO,__('Nothing to Update'));

        //$this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index')));
    }

    // public function action_seoname_user()
    // {
    //   $user = new Model_User();
    //   $users = $user->find_all();

    //   foreach ($users as $user) {
    //     if($user->seoname == NULL)
    //     {
          
    //       try {
    //         $user->seoname = $user->gen_seo_title($user->name);
    //         $user->save();
    //       } catch (Exception $e) {
            
    //       }
    //     }
    //   }
    // }


    /**
     * This function will upgrate DB that didn't existed in verisons below 2.0.6
     * changes added: config for custom field
     */
    public function action_207()
    {
      // build array with new (missing) configs
        $configs = array(array('config_key'     =>'fields',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>''),
                         array('config_key'     =>'alert_terms',
                               'group_name'     =>'general', 
                               'config_value'   =>''),
                         );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);

        // message
        if($return_conf)
            Alert::set(Alert::SUCCESS,__('Updated'));
        else
            Alert::set(Alert::INFO,__('Nothing to Update'));

        // $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index')));
    }

    /**
     * This function will upgrate DB that didn't existed in verisons below 2.0.6
     * changes added: config for custom field
     */
    public function action_latest()
    {
      
        $versions = core::config('versions');
        $download_link = $versions[0]['download'];
        $version = key($versions);

      //@todo do a walidation of downloaded file and if its downloaded, trow error if something is worong
      // review all to be automatic

        $zip_file = "https://github.com/open-classifieds/openclassifieds2/archive/2.0.7.zip"; // URL of download zip file
      $dir_local = DOCROOT."update"; // update dir 
      $fname = $dir_local."/2.0.7.zip";
      
      //check if exists file name
      if (file_exists($fname)) 
      { 
        unlink($fname); 
      }
      //create dir if doesnt exists
      if (!is_dir($dir_local)) 
      { 
        mkdir($dir_local, 0775);
      }
      
      //download file
      $download = file_put_contents($fname, fopen($zip_file, 'r'));

      //unpack zip
      $zip = new ZipArchive;

      // open zip file, and extract to dir
      if ($zip_open = $zip->open($fname)) 
      {
          $zip->extractTo($dir_local);
          $zip->close();
      } 
      else 
      {
          Alert::set(Alert::ALERT, $fname.' '.__('Zip file faild to extract, please try again.'));
          $this->request->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
      }

      //file list to be replaced
      //move specific files
      $list = array('/oc/config/routes.php','/oc/classes/','/oc/modules/','/oc/vendor/','/themes/','/languages/');

      // function that copies files/folders recursively 
      // copy a directory and all subdirectories and files (recursive) 
      // void recurse_copy( str 'source directory', str 'destination directory' [, bool 'overwrite existing files'] ) 
      function recurse_copy($basePath, $source, $dest, $overwrite = false){ 
          if(!is_dir($basePath . $dest)) //Lets just make sure our new folder is already created. Alright so its not efficient to check each time... bite me 
          mkdir($basePath . $dest); 
          if($handle = opendir($basePath . $source)){        // if the folder exploration is sucsessful, continue 
              while(false !== ($file = readdir($handle))){ // as long as storing the next file to $file is successful, continue 
                  if($file != '.' && $file != '..'){ 
                      $path = $source . '/' . $file; 
                      if(is_file($basePath . $path)){ 
                          if(!is_file($basePath . $dest . '/' . $file) || $overwrite) 
                          if(!@copy($basePath . $path, $basePath . $dest . '/' . $file)){ 
                              echo '<font color="red">File ('.$path.') could not be copied, likely a permissions problem.</font>'; 
                          } 
                      } elseif(is_dir($basePath . $path)){ 
                          if(!is_dir($basePath . $dest . '/' . $file)) 
                          mkdir($basePath . $dest . '/' . $file); // make subdirectory before subdirectory is copied 
                          recurse_copy($basePath, $path, $dest . '/' . $file, $overwrite); //recurse! 
                      } 
                  } 
              } 
              closedir($handle); 
          } 
      }
      
      
      foreach ($list as $root) {
        
        if(is_file($dir_local.'/openclassifieds2-2.0.7'.$root))
          copy($dir_local.'/openclassifieds2-2.0.7'.$root, DOCROOT.$root);
        elseif(is_dir($dir_local.'/openclassifieds2-2.0.7'.$root))
          recurse_copy(DOCROOT, 'update/openclassifieds2-2.0.7'.$list[4], $list[4], TRUE);   
      }
      
      //call update actions 203,205,206,207 
      $this->action_203();
      $this->action_205();
      $this->action_206();
      $this->action_207();
      
      //delete file when all finished
      function rrmdir($dir_delete) {
        if (is_dir($dir_delete)) 
        {
           $objects = scandir($dir_delete);
           foreach ($objects as $object) 
           {
             if ($object != "." && $object != "..") 
             {
               if (filetype($dir_delete."/".$object) == "dir") rrmdir($dir_delete."/".$object); else unlink($dir_delete."/".$object);
             }
           }
           reset($objects);
           rmdir($dir_delete);
         }
      }
      rrmdir($dir_local);
// d($download);
     
    }

    
}