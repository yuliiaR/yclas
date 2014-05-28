<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Update controllers 
 *
 * @package    OC
 * @category   Update
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
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
            $this->template->title = __('Updates');
            Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

            //version numbers in a key value
            $version_nums = array();
            foreach ($versions as $version=>$values)
                $version_nums[] = $version;

            $latest_version = current($version_nums);
            $latest_version_update = next($version_nums);


            //check if we have latest version of OC and using the previous version then we allow to auto update
            if ($latest_version!=core::VERSION AND core::VERSION == $latest_version_update )
                Alert::set(Alert::ALERT,__('You are not using latest version, please update.').
                    '<br/><br/><a class="btn btn-primary update_btn" href="'.Route::url('oc-panel',array('controller'=>'update','action'=>'latest')).'">
                '.__('Update').'</a>');
            elseif ($latest_version!=core::VERSION AND core::VERSION != $latest_version_update )
                Alert::set(Alert::ALERT,__('You are using an old version, can not update automatically, please update manually.'));

            //pass to view from local versions.php         
            $this->template->content = View::factory('oc-panel/pages/tools/versions',array('versions'       =>$versions,
                                                                                           'latest_version' =>key($versions)));
        }        

    }

    /**
     * This function will upgrade configs that didn't existed in versions prior to 2.0.3 
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

    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.0.5 
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
        
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".$prefix."subscribers` (
                    `id_subscribe` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_user` int(10) unsigned NOT NULL,
                    `id_category` int(10) unsigned NOT NULL DEFAULT '0',
                    `id_location` int(10) unsigned NOT NULL DEFAULT '0',
                    `min_price` decimal(14,3) NOT NULL DEFAULT '0',
                    `max_price` decimal(14,3) NOT NULL DEFAULT '0',
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id_subscribe`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=".$charset.";")->execute();
        }catch (exception $e) {}
        
        // remove INDEX from content table
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".$prefix."content` DROP INDEX `".$prefix."content_UK_seotitle`")->execute();
        }catch (exception $e) {}
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.0.5 
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

        
    }


    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.0.6
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
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.0.7
     * changes added: config for advanced search by description
     */
    public function action_21()
    {
        $prefix = Database::instance()->table_prefix();
        $config_db = Kohana::$config->load('database');
        $charset = $config_db['default']['charset'];

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."users` ADD  `hybridauth_provider_name` VARCHAR( 40 ) NULL DEFAULT NULL ,ADD  `hybridauth_provider_uid` VARCHAR( 245 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"CREATE UNIQUE INDEX ".$prefix."users_UK_provider_AND_uid on ".$prefix."users (hybridauth_provider_name, hybridauth_provider_uid)")->execute();
        }catch (exception $e) {}
        
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS  `".$prefix."posts` (
                  `id_post` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `id_user` int(10) unsigned NOT NULL,
                  `title` varchar(245) NOT NULL,
                  `seotitle` varchar(245) NOT NULL,
                  `description` text NOT NULL,
                  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `status` tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id_post`) USING BTREE,
                  UNIQUE KEY `".$prefix."posts_UK_seotitle` (`seotitle`)
                ) ENGINE=InnoDB DEFAULT CHARSET=".$charset.";")->execute();
        }catch (exception $e) {}


        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'search_by_description',
                               'group_name'     =>'general', 
                               'config_value'   => 0),
                        array('config_key'     =>'blog',
                               'group_name'     =>'general', 
                               'config_value'   => 0),
                        array('config_key'     =>'minify',
                               'group_name'     =>'general', 
                               'config_value'   => 0),
                        array('config_key'     =>'parent_category',
                               'group_name'     =>'advertisement', 
                               'config_value'   => 1),
                        array('config_key'     =>'blog_disqus',
                               'group_name'     =>'general', 
                               'config_value'   => ''),
                        array('config_key'     =>'config',
                               'group_name'     =>'social', 
                               'config_value'   =>'{"debug_mode":"0","providers":{
                                                          "OpenID":{"enabled":"1"},
                                                          "Yahoo":{"enabled":"0","keys":{"id":"","secret":""}},
                                                          "AOL":{"enabled":"1"}
                                                          ,"Google":{"enabled":"0","keys":{"id":"","secret":""}},
                                                          "Facebook":{"enabled":"0","keys":{"id":"","secret":""}},
                                                          "Twitter":{"enabled":"0","keys":{"key":"","secret":""}},
                                                          "Live":{"enabled":"0","keys":{"id":"","secret":""}},
                                                          "MySpace":{"enabled":"0","keys":{"key":"","secret":""}},
                                                          "LinkedIn":{"enabled":"0","keys":{"key":"","secret":""}},
                                                          "Foursquare":{"enabled":"0","keys":{"id":"","secret":""}}},
                                                      "base_url":"",
                                                      "debug_file":""}'));

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);

        
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1
     */
    public function action_211()
    {
      // build array with new (missing) configs
        $configs = array(array('config_key'     =>'related',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>'5'),
                        array('config_key'     =>'faq',
                               'group_name'     =>'general', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'faq_disqus',
                               'group_name'     =>'general', 
                               'config_value'   =>''),
                         );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs); 
       
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.3
     */
    public function action_214()
    {        
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'sort_by',
                               'group_name'     =>'general', 
                               'config_value'   =>'published-desc'),
                         array('config_key'     =>'map_pub_new',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>'0'), 
                        );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.5
     */
    public function action_215()
    {        
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'qr_code',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>'0'),
                         array('config_key'     =>'black_list',
                               'group_name'     =>'general', 
                               'config_value'   =>'1'),
                         array('config_key'     =>'stock',
                               'group_name'     =>'payment', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'fbcomments',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>''),
                        );
        $contents = array(array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is sold on [SITE.NAME]!',
                               'seotitle'=>'adssold',
                               'description'=>"Order ID: [ORDER.ID]\n\nProduct ID: [PRODUCT.ID]\n\nPlease check your bank account for the incoming payment.\n\nClick here to visit [URL.AD]", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is purchased on [SITE.NAME]!',
                               'seotitle'=>'adspurchased',
                               'description'=>"Order ID: [ORDER.ID]\n\nProduct ID: [PRODUCT.ID]\n\nFor any inconvenience please contact administrator of [SITE.NAME], with a details provided abouve.\n\nClick here to visit [URL.AD]", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is out of stock on [SITE.NAME]!',
                               'seotitle'=>'outofstock',
                               'description'=>"Hello [USER.NAME],\n\nWhile your ad is out of stock, it is unavailable for others to see. If you wish to increase stock and activate, please follow this link [URL.EDIT].\n\nRegards!", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),);

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);

        $prefix = Database::instance()->table_prefix();

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."users` ADD `subscriber` tinyint(1) NOT NULL DEFAULT '1'")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."ads` ADD `stock` int(10) unsigned DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO  `".$prefix."roles` (`id_role`, `name`, `description`) VALUES (7, 'moderator', 'Limited access')")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO  `".$prefix."access` (`id_access`, `id_role`, `access`) VALUES 
                                                                         (17, 7, 'location.*'),(16, 7, 'profile.*'),(15, 7, 'content.*'),(14, 7, 'stats.user'),
                                                                         (13, 7, 'blog.*'),(12, 7, 'translations.*'),(11, 7, 'ad.*'),
                                                                         (10, 7, 'widgets.*'),(9, 7, 'menu.*'),(8, 7, 'category.*')")->execute();
        }catch (exception $e) {}

    }


    //public function action_216()
    //{  }
    
    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.7
     */
    public function action_217()
    {        
        $prefix = Database::instance()->table_prefix();

        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."posts` ADD  `id_post_parent` INT NULL DEFAULT NULL AFTER  `id_user`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."posts` ADD  `ip_address` FLOAT NULL DEFAULT NULL AFTER  `created`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."posts` ADD  `id_forum` INT NULL DEFAULT NULL AFTER  `id_post_parent`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".$prefix."posts` ENGINE = MYISAM ")->execute();
        }catch (exception $e) {}
        

        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS  `".$prefix."forums` (
                      `id_forum` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `name` varchar(145) NOT NULL,
                      `order` int(2) unsigned NOT NULL DEFAULT '0',
                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      `id_forum_parent` int(10) unsigned NOT NULL DEFAULT '0',
                      `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
                      `seoname` varchar(145) NOT NULL,
                      `description` varchar(255) NULL,
                      PRIMARY KEY (`id_forum`) USING BTREE,
                      UNIQUE KEY `".$prefix."forums_IK_seo_name` (`seoname`)
                    ) ENGINE=MyISAM")->execute();

        // build array with new (missing) configs
        
        //set sitemap to 0
        Model_Config::set_value('sitemap','on_post',0);

        $configs = array(
                         array('config_key'     =>'forums',
                               'group_name'     =>'general', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'ocacu',
                               'group_name'     =>'general', 
                               'config_value'   =>'0'), 
                        );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);

    }


    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.8
     */
    public function action_218()
    {   
        //call update previous versions
        $this->action_203();
        $this->action_205();
        $this->action_206();
        $this->action_207();
        $this->action_21();
        $this->action_211();
        $this->action_214();
        $this->action_215();
        $this->action_217();

        $prefix = Database::instance()->table_prefix();

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".$prefix."config DROP INDEX ".$prefix."config_IK_group_name_AND_config_key")->execute();
        }catch (exception $e) {}
        
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".$prefix."config ADD PRIMARY KEY (config_key);")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"CREATE UNIQUE INDEX ".$prefix."config_UK_group_name_AND_config_key ON ".$prefix."config(`group_name` ,`config_key`)")->execute();
        }catch (exception $e) {}

        $configs = array(
                         array('config_key'     =>'login_to_post',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>'0'),  
                        );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        
        //clean cache
        Cache::instance()->delete_all();
        Theme::delete_minified();
        
        //delete old files from 322
        File::delete(APPPATH.'ko322');
        File::delete(MODPATH.'auth');
        File::delete(MODPATH.'cache');
        File::delete(MODPATH.'database');
        File::delete(MODPATH.'image');
        File::delete(MODPATH.'orm');
        File::delete(MODPATH.'unittest');

        //deactivate maintenance mode
        Model_Config::set_value('general','maintenance',0);

        Alert::set(Alert::SUCCESS, __('Software Updated to latest version!'));
        $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index'))); 
    }


    /**
     * This function will upgrade DB that didn't existed in previous versions
     * @FIXME @TOFIX  NEVER USED / NEVER CALLED
     */
    public function action_latest()
    {        
        //activate maintenance mode
        Model_Config::set_value('general','maintenance',1);

        $versions = core::config('versions'); //loads OC software version array 
        $download_link = $versions[key($versions)]['download']; //get latest download link
        $version = key($versions); //get latest version

        $update_src_dir = DOCROOT."update"; // update dir 
        $fname = $update_src_dir."/".$version.".zip"; //full file name
        $folder_prefix = 'openclassifieds2-';
        $dest_dir = DOCROOT; //destination directory
        
        //check if exists file name
        if (file_exists($fname))  
            unlink($fname); 

        //create dir if doesnt exists
        if (!is_dir($update_src_dir))  
            mkdir($update_src_dir, 0775); 
          
        //verify we could get the zip file
        $file_content = core::curl_get_contents($download_link);
        if ($file_content == FALSE)
        {
            Alert::set(Alert::ALERT, __('We had a problem downloading latest version, try later please.'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //Write the file
        file_put_contents($fname, $file_content);

        //unpack zip
        $zip = new ZipArchive;
        // open zip file, and extract to dir
        if ($zip_open = $zip->open($fname)) 
        {
            $zip->extractTo($update_src_dir);
            $zip->close();  
        }   
        else 
        {
            Alert::set(Alert::ALERT, $fname.' '.__('Zip file failed to extract, please try again.'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //files to be replaced / move specific files
        $copy_list = array('oc/config/routes.php',
                          'oc/classes/',
                          'oc/modules/',
                          'oc/vendor/',
                          'oc/kohana/',
                          'oc/bootstrap.php',
                          'themes/',
                          'languages/',
                          'index.php',
                          'README.md',);
      
        foreach ($copy_list as $dest_path) 
        { 
            $source = $update_src_dir.'/'.$folder_prefix.$version.'/'.$dest_path;
            $dest = $dest_dir.$dest_path;
            
            File::copy($source, $dest, TRUE);
        }
          
        //delete file when all finished
        File::delete($update_src_dir);

        //clean the cache so kohana file finder wont cache the search
        Cache::instance()->delete_all();

        //update themes, different request so doesnt time out
        $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'themes','id'=>str_replace('.', '', $version)))); 
      
    }

        /**
     * updates all themes to latest version from API license
     * @return void 
     */
    public function action_themes()
    {
        //activate maintenance mode
        Model_Config::set_value('general','maintenance',1);

        $licenses = array();

        //getting the licenses unique. to avoid downloading twice
        $themes = core::config('theme');
        foreach ($themes as $theme) 
        {
            $settings = json_decode($theme,TRUE);
            if (isset($settings['license']))
            {
                if (!in_array($settings['license'], $licenses))
                    $licenses[] = $settings['license'];
            }
        }

        //for each unique license then download!
        foreach ($licenses as $license) 
            Theme::download($license); 
        
        Alert::set(Alert::SUCCESS, __('Themes Updated'));

        //if theres version passed we redirect here to finish the update, if no version means was called directly
        if ( ($version = $this->request->param('id')) !==NULL)
            $this->request->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>$version)));   
        else
        {
            //deactivate maintenance mode
            Model_Config::set_value('general','maintenance',0);
            $this->request->redirect(Route::url('oc-panel', array('controller'=>'theme', 'action'=>'index'))); 
        }
                    
    }
    
}
