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

    static $db_prefix = NULL;
    static $db_charset = NULL;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

        self::$db_prefix  = Core::config('database.default.table_prefix');
        self::$db_charset = Core::config('database.default.charset');
    }

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
            //if ($latest_version!=core::VERSION AND core::VERSION == $latest_version_update )
            if ($latest_version!=core::VERSION)
                Alert::set(Alert::ALERT,__('You are not using latest version, please update.').
                    '<br/><br/><a class="btn btn-primary update_btn" href="'.Route::url('oc-panel',array('controller'=>'update','action'=>'latest')).'">
                '.__('Update').'</a>');
            //elseif ($latest_version!=core::VERSION AND core::VERSION != $latest_version_update )
                //Alert::set(Alert::ALERT,__('You are using an old version, can not update automatically, please update manually.'));

            //pass to view from local versions.php         
            $this->template->content = View::factory('oc-panel/pages/tools/versions',array('versions'       =>$versions,
                                                                                           'latest_version' =>key($versions)));
        }        

    }

    /**
     * STEP 1
     * Downloads and extracts latest version
     */
    public function action_latest()
    {    
        //save in a session the current version so we can selective update the DB later
        Session::instance()->set('update_from_version', Core::VERSION);

        $versions       = core::config('versions'); //loads OC software version array 
        $last_version   = key($versions); //get latest version
        $download_link  = $versions[$last_version]['download']; //get latest download link
        $update_src_dir = DOCROOT.'update'; // update dir 
        $file_name      = $update_src_dir.'/'.$last_version.'.zip'; //full file name
        
        
        //check if exists already the download, if does delete
        if (file_exists($file_name))  
            unlink($file_name); 

        //create update dir if doesnt exists
        if (!is_dir($update_src_dir))  
            mkdir($update_src_dir, 0775); 
          
        //verify we could get the zip file
        $file_content = core::curl_get_contents($download_link);
        if ($file_content == FALSE)
        {
            Alert::set(Alert::ALERT, __('We had a problem downloading latest version, try later please.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //Write the file
        file_put_contents($file_name, $file_content);

        //unpack zip
        $zip = new ZipArchive;
        // open zip file, and extract to dir
        if ($zip_open = $zip->open($file_name)) 
        {
            $zip->extractTo($update_src_dir);
            $zip->close();  
        }   
        else 
        {
            Alert::set(Alert::ALERT, $file_name.' '.__('Zip file failed to extract, please try again.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //delete downloaded file
        unlink($file_name);
        
        //move files in different request so more time
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'files'))); 
      
    }

    /**
     * STEP 2
     * this controller moves the extracted files
     */
    public function action_files()
    {
        ignore_user_abort(true);

        $folder_prefix  = 'openclassifieds2-';//the folder inside the ZIP file UGLY!! TODO get folder name
        $last_version   = key(core::config('versions')); //get latest version
        $update_src_dir = DOCROOT.'update'; // update dir 
        //this sucks!! lets read update_src_dir needs to be only 1 folder, if only 1 folder then use that folder, if more than 1 folder as from use update_src_dir
        $from           = $update_src_dir.'/'.$folder_prefix.$last_version;

        //can we access the folder?
        if (is_dir($from))
        {
            //list of files to ignore the copy, TODO ignore languages folder?
            $ignore_list = array('robots.txt',
                            'oc/config/auth.php',
                            'oc/config/database.php',
                            '.htaccess',
                            'sitemap.xml.gz',
                            'sitemap.xml',
                            'install/install.lock',
                            );

            //activate maintenance mode since we are moving files...
            Model_Config::set_value('general','maintenance',1);
            //copy all except the ignored files and only if files different size
            File::copy($from, DOCROOT, 2, $ignore_list);
        }
        else
        {
            Alert::set(Alert::ALERT, $from.' '.sprintf(__('Update folder `%s` not found.'),$from));
            $this->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }
          
        //delete update files when all finished
        File::delete($update_src_dir);

        //clean cache
        Core::delete_cache();

        //deactivate maintenance mode
        Model_Config::set_value('general','maintenance',0);

        //update the DB in different request
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'database'))); 
    }

    /**
     *  STEP 3
     *  Updates the DB using the functions action_2XX
     *  they are actions, just in case you want to launch the update of a specific release like /oc-panel/update/218 for example
     */
    public function action_database()
    {
        ignore_user_abort(true);
        
        //activate maintenance mode
        Model_Config::set_value('general','maintenance',1);

        //getting the version from where we are upgrading
        $update_from_version = Session::instance()->get('update_from_version',Core::VERSION);

        $from_version = str_replace('.', '',$update_from_version);
        $to_version   = str_replace('.', '',Core::VERSION);

        for ($version=203; $version <= $to_version ; $version++) 
        { 
            if (method_exists($this,'action_'.$version))
                call_user_method('action_'.$version, $this);
        }

        //deactivate maintenance mode
        Model_Config::set_value('general','maintenance',0);

        Alert::set(Alert::SUCCESS, __('Software DB Updated to latest version!'));

        //clean cache
        Core::delete_cache();

        //TODO maybe a setting that forces the update of the themes?
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'themes'))); 
    }
    
    /**
     * STEP 4 and last
     * updates all themes to latest version from API license
     * @return void 
     */
    public function action_themes()
    {
        
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

        //only if theres work to do ;)
        if (count($licenses)>0)
        {
            //activate maintenance mode
            Model_Config::set_value('general','maintenance',1);

            //for each unique license then download!
            foreach ($licenses as $license) 
                Theme::download($license); 
            
            Alert::set(Alert::SUCCESS, __('Themes Updated'));

            //deactivate maintenance mode
            Model_Config::set_value('general','maintenance',0);

            //clean cache
            Core::delete_cache();
        }
        
        //finished the entire update process
        $this->redirect(Route::url('oc-panel', array('controller'=>'theme', 'action'=>'index'))); 
                    
    }


    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.2.0
     */
    public function action_220()
    {   
        //updating contents replacing . for _
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".$prefix."content SET seotitle=REPLACE(seotitle,'.','-') WHERE type='email'")->execute();
        }catch (exception $e) {}

        //cleaning emails not in use
        try
        {
            DB::query(Database::DELETE,"DELETE FROM ".self::$db_prefix."content WHERE seotitle='user.new' AND type='email'")->execute();
        }catch (exception $e) {}

        //updating contents bad names
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='ads-sold' WHERE seotitle='adssold' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='out-of-stock' WHERE seotitle='outofstock' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='ads-purchased' WHERE seotitle='adspurchased' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='ads-purchased' WHERE seotitle='adspurchased' AND type='email'")->execute();
        }catch (exception $e) {}
        //end updating emails
        

        //order transaction
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD  `txn_id` VARCHAR( 255 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        

        //ip_address from float to bigint
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` CHANGE last_ip last_ip BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."visits` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}

        //crontab table
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".self::$db_prefix."crontab` (
                    `id_crontab` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `name` varchar(50) NOT NULL,
                      `period` varchar(50) NOT NULL,
                      `callback` varchar(140) NOT NULL,
                      `params` varchar(255) DEFAULT NULL,
                      `description` varchar(255) DEFAULT NULL,
                      `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `date_started` datetime  DEFAULT NULL,
                      `date_finished` datetime  DEFAULT NULL,
                      `date_next` datetime  DEFAULT NULL,
                      `times_executed`  bigint DEFAULT '0',
                      `output` varchar(50) DEFAULT NULL,
                      `running` tinyint(1) NOT NULL DEFAULT '0',
                      `active` tinyint(1) NOT NULL DEFAULT '1',
                      PRIMARY KEY (`id_crontab`),
                      UNIQUE KEY `".self::$db_prefix."crontab_UK_name` (`name`)
                  ) ENGINE=MyISAM;")->execute();
        }catch (exception $e) {}

        //crontabs
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('Sitemap', '* 3 * * *', 'Sitemap::generate', 'TRUE', 'Regenerates the sitemap everyday at 3am',1),
                                    ('Clean Cache', '* 5 * * *', 'Core::delete_cache', NULL, 'Once day force to flush all the cache.', 1),
                                    ('Optimize DB', '* 4 1 * *', 'Core::optimize_db', NULL, 'once a month we optimize the DB', 1);")->execute();
        }catch (exception $e) {}

        //new mails
        $contents = array(array('order'=>0,
                                'title'=>'Reciept for [ORDER.DESC] #[ORDER.ID]',
                               'seotitle'=>'new-order',
                               'description'=>"Hello [USER.NAME],Thanks for buying [ORDER.DESC].\n\nPlease complete the payment here [URL.CHECKOUT]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                        );

        Model_Content::content_array($contents);

        //new payments...
        $configs = array(
                         array('config_key'     =>'bitpay_apikey',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'paymill_private',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'paymill_public',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'stripe_public',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'stripe_private',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'stripe_address',
                               'group_name'     =>'payment', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'alternative',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'authorize_sandbox',
                               'group_name'     =>'payment', 
                               'config_value'   =>'0'), 
                         array('config_key'     =>'authorize_login',
                               'group_name'     =>'payment', 
                               'config_value'   =>''), 
                         array('config_key'     =>'authorize_key',
                               'group_name'     =>'payment', 
                               'config_value'   =>''),
                         array('config_key'     =>'elastic_active',
                               'group_name'     =>'email', 
                               'config_value'   =>0),
                         array('config_key'     =>'elastic_username',
                               'group_name'     =>'email', 
                               'config_value'   =>''),
                         array('config_key'     =>'elastic_password',
                               'group_name'     =>'email', 
                               'config_value'   =>''),

                        );

        Model_Config::config_array($configs);
        
        //delete old files from 323
        File::delete(APPPATH.'ko323');
        File::delete(APPPATH.'classes/image/');

        //delete modules since now they are part of module common
        File::delete(MODPATH.'pagination');
        File::delete(MODPATH.'breadcrumbs');
        File::delete(MODPATH.'formmanager');
        File::delete(MODPATH.'mysqli');
       
    }
    
    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.8
     */
    public function action_218()
    {   


        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".self::$db_prefix."config DROP INDEX ".self::$db_prefix."config_IK_group_name_AND_config_key")->execute();
        }catch (exception $e) {}
        
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".self::$db_prefix."config ADD PRIMARY KEY (config_key);")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"CREATE UNIQUE INDEX ".self::$db_prefix."config_UK_group_name_AND_config_key ON ".self::$db_prefix."config(`group_name` ,`config_key`)")->execute();
        }catch (exception $e) {}

        $configs = array(
                         array('config_key'     =>'login_to_post',
                               'group_name'     =>'advertisement', 
                               'config_value'   =>'0'),  
                        );

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        
        //delete old files from 322
        File::delete(APPPATH.'ko322');
        File::delete(MODPATH.'auth');
        File::delete(MODPATH.'cache');
        File::delete(MODPATH.'database');
        File::delete(MODPATH.'image');
        File::delete(MODPATH.'orm');
        File::delete(MODPATH.'unittest');

    }
    
    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.7
     */
    public function action_217()
    {        

        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ADD  `id_post_parent` INT NULL DEFAULT NULL AFTER  `id_user`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ADD  `ip_address` FLOAT NULL DEFAULT NULL AFTER  `created`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ADD  `id_forum` INT NULL DEFAULT NULL AFTER  `id_post_parent`")->execute();
        }catch (exception $e) {}
        try
        {    
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ENGINE = MYISAM ")->execute();
        }catch (exception $e) {}
        

        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS  `".self::$db_prefix."forums` (
                      `id_forum` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `name` varchar(145) NOT NULL,
                      `order` int(2) unsigned NOT NULL DEFAULT '0',
                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      `id_forum_parent` int(10) unsigned NOT NULL DEFAULT '0',
                      `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
                      `seoname` varchar(145) NOT NULL,
                      `description` varchar(255) NULL,
                      PRIMARY KEY (`id_forum`) USING BTREE,
                      UNIQUE KEY `".self::$db_prefix."forums_IK_seo_name` (`seoname`)
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
                               'seotitle'=>'ads-sold',
                               'description'=>"Order ID: [ORDER.ID]\n\nProduct ID: [PRODUCT.ID]\n\nPlease check your bank account for the incoming payment.\n\nClick here to visit [URL.AD]", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is purchased on [SITE.NAME]!',
                               'seotitle'=>'ads-purchased',
                               'description'=>"Order ID: [ORDER.ID]\n\nProduct ID: [PRODUCT.ID]\n\nFor any inconvenience please contact administrator of [SITE.NAME], with a details provided above.\n\nClick here to visit [URL.AD]", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is out of stock on [SITE.NAME]!',
                               'seotitle'=>'out-of-stock',
                               'description'=>"Hello [USER.NAME],\n\nWhile your ad is out of stock, it is unavailable for others to see. If you wish to increase stock and activate, please follow this link [URL.EDIT].\n\nRegards!", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),);

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);


        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `subscriber` tinyint(1) NOT NULL DEFAULT '1'")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` ADD `stock` int(10) unsigned DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO  `".self::$db_prefix."roles` (`id_role`, `name`, `description`) VALUES (7, 'moderator', 'Limited access')")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO  `".self::$db_prefix."access` (`id_access`, `id_role`, `access`) VALUES 
                                                                         (17, 7, 'location.*'),(16, 7, 'profile.*'),(15, 7, 'content.*'),(14, 7, 'stats.user'),
                                                                         (13, 7, 'blog.*'),(12, 7, 'translations.*'),(11, 7, 'ad.*'),
                                                                         (10, 7, 'widgets.*'),(9, 7, 'menu.*'),(8, 7, 'category.*')")->execute();
        }catch (exception $e) {}

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
     * This function will upgrade DB that didn't existed in versions prior to 2.0.7
     * changes added: config for advanced search by description
     */
    public function action_210()
    {
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD  `hybridauth_provider_name` VARCHAR( 40 ) NULL DEFAULT NULL ,ADD  `hybridauth_provider_uid` VARCHAR( 245 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"CREATE UNIQUE INDEX ".self::$db_prefix."users_UK_provider_AND_uid on ".self::$db_prefix."users (hybridauth_provider_name, hybridauth_provider_uid)")->execute();
        }catch (exception $e) {}
        
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS  `".self::$db_prefix."posts` (
                  `id_post` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `id_user` int(10) unsigned NOT NULL,
                  `title` varchar(245) NOT NULL,
                  `seotitle` varchar(245) NOT NULL,
                  `description` text NOT NULL,
                  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `status` tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id_post`) USING BTREE,
                  UNIQUE KEY `".self::$db_prefix."posts_UK_seotitle` (`seotitle`)
                ) ENGINE=InnoDB DEFAULT CHARSET=".self::$db_charset.";")->execute();
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
                               'seotitle'=>'ads_subscribers',
                               'description'=>"Hello,\n\nYou may be interested in this one [AD.TITLE]!\n\nYou can visit this link to see advertisement [URL.AD]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is created on [SITE.NAME]!',
                               'seotitle'=>'ads-to-admin',
                               'description'=>"Click here to visit [URL.AD]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'));

        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);

        
        
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".self::$db_prefix."subscribers` (
                    `id_subscribe` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_user` int(10) unsigned NOT NULL,
                    `id_category` int(10) unsigned NOT NULL DEFAULT '0',
                    `id_location` int(10) unsigned NOT NULL DEFAULT '0',
                    `min_price` decimal(14,3) NOT NULL DEFAULT '0',
                    `max_price` decimal(14,3) NOT NULL DEFAULT '0',
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id_subscribe`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=".self::$db_charset.";")->execute();
        }catch (exception $e) {}
        
        // remove INDEX from content table
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."content` DROP INDEX `".self::$db_prefix."content_UK_seotitle`")->execute();
        }catch (exception $e) {}
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
                               'seotitle'=>'user-profile-contact',
                               'description'=>"User [EMAIL.SENDER] [EMAIL.FROM], have a message for you: \n\n [EMAIL.SUBJECT] \n\n[EMAIL.BODY]. \n\n Regards!",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'));
        
        // returns TRUE if some config is saved 
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);

    }


}
