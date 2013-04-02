<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Mixed tools for admin
 *
 * @package    OC
 * @category   Tools
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Tools extends Auth_Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Tools'))->set_url(Route::url('oc-panel',array('controller'  => 'tools'))));
        
    }
    
    public function action_index()
    {
        //@todo just a view with links?
        Request::current()->redirect(Route::url('oc-panel',array('controller'  => 'tools','action'=>'sitemap')));  
    }


    public function action_optimize()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Optimize DB')));
        
        $this->template->title = __('Optimize DB');

        $db = Database::instance('default');

        //force optimize all tables
        if (Core::get('force')==1)
        {
            $tables = $db->query(Database::SELECT, 'SHOW TABLES');

            foreach ($tables as $table)
            {
                $table = array_values($table);
                $to[] = $table[0];
            }
            $db->query(Database::SELECT, 'OPTIMIZE TABLE '.implode(', ', $to));
            Alert::set(Alert::SUCCESS,__('Database Optimized'));
        }


        //get tables names and the size and the index
        $total_space = 0;
        $total_gain  = 0;
        $tables_info = array();
        
        $tables = $db->query(Database::SELECT, 'SHOW TABLE STATUS');

        foreach ($tables as $table) 
        {
            $tot_data = $table['Data_length'];
            $tot_idx  = $table['Index_length'];
            $tot_free = $table['Data_free'];

            $tables_info[] = array( 'name'  => $table['Name'],
                                    'rows'  => $table['Rows'],
                                    'space' => round (($tot_data + $tot_idx) / 1024,3),
                                    'gain'  => round ($tot_free / 1024,3),
                                    );

            $total_space += ($tot_data + $tot_idx) / 1024;
            $total_gain += $tot_free / 1024;
        }


        $this->template->content = View::factory('oc-panel/pages/tools/optimize',array('tables'=>$tables_info,
                                                                                        'total_gain'=>$total_gain,
                                                                                        'total_space'=>$total_space,));
    }

    public function action_cache()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Cache')));
        
        $this->template->title = __('Cache');

        $cache_config = Core::config('cache.'.Core::config('cache.default'));

        //force regenerate sitemap
        if (Core::get('force')==1)
        {
            Cache::instance()->delete_all();
            Alert::set(Alert::SUCCESS,__('Cache deleted'));

        }
        $this->template->content = View::factory('oc-panel/pages/tools/cache',array('cache_config'=>$cache_config));
    }

    public function action_phpinfo()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('PHP Info')));
        
        $this->template->title = __('PHP Info');

        //getting the php info clean!
        ob_start();                                                                                                        
        phpinfo();                                                                                                     
        $phpinfo = ob_get_contents();                                                                                         
        ob_end_clean();  
        //strip the body html                                                                                                  
        $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
        //adding our class
        $phpinfo = str_replace('<table', '<table class="table table-striped  table-bordered"', $phpinfo);

        $this->template->content = View::factory('oc-panel/pages/tools/phpinfo',array('phpinfo'=>$phpinfo));

    }


    public function action_sitemap()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Sitemap')));
        
        $this->template->title = __('Sitemap');

        
        // all sitemap config values
        $sitemapconfig = new Model_Config();
        $config = $sitemapconfig->where('group_name', '=', 'sitemap')->find_all();
      
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
            Alert::set(Alert::SUCCESS, __('Success, Sitemap Configuration updated'));
            $this->request->redirect(Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap')));
        }

        //force regenerate sitemap
        if (Core::get('force')==1)
            Alert::set(Alert::SUCCESS, Sitemap::generate(TRUE));

        $this->template->content = View::factory('oc-panel/pages/tools/sitemap');
    }


    public function action_migration()
    {
        //@todo improve
        //flow: ask for new connection, if success we store it ina  config as an array.
        //then we display the tables with how many rows --> new view, bottom load the db connection form in case they want to change it
        //in the form ask to do diet in current DB cleanins visits users posts inactive?
        //Migration button
            //on submit 
            // create config group migration to store in which ID was stuck (if happens)
            // save ids migration for maps in configs?
            // do migration using iframe this

        $this->template->title   = __('Open Classifieds migration');
        Breadcrumbs::add(Breadcrumb::factory()->set_title(ucfirst(__('Migration'))));

        if ($this->request->post())
        {
            $db_config = array (
                'type' => 'mysql',
                'connection' => 
                array (
                    'hostname' => Core::post('hostname'),
                    'database' => Core::post('database'),
                    'username' => Core::post('username'),
                    'password' => Core::post('password'),
                    'persistent' => false,
                ),
                'table_prefix' => Core::post('table_prefix'),
                'charset' => Core::post('charset'),
                'caching' => false,
                'profiling' => false,
            );

            try
            {

                //connect DB
                $db = Database::instance('migrate', $db_config);

                //verify tables in DB
                $pf = Core::post('table_prefix');
                $migration_tables = array($pf.'accounts',$pf.'categories',$pf.'locations',$pf.'posts',$pf.'postshits');
                
                $tables = $db->query(Database::SELECT, 'SHOW TABLES;');
                
            }
            catch (Exception $e)
            {
                Alert::set(Alert::ERROR, __('Review Database connection params'));
                break(1);
            }   
           
            //verify tables in DB
            foreach ($tables as $table => $value) 
            {
                $val = array_values($value);
                $t[] = $val[0];                        
            }
            $tables = $t;

            $match_tables = TRUE;
            foreach ($migration_tables as $t) 
            {
                if(!in_array($t, $tables))
                {
                    $match_tables = FALSE;
                    Alert::set(Alert::ERROR, ('Table '.$t.'not found'));
                }
                    
            }
            //end tables verification
            
            
            if ($match_tables)
            {
                //start migration
                $this->migrate($db,$pf);
                Alert::set(Alert::SUCCESS, ('oh yeah!'));
            }
            
        }
        else
        {
            $db_config = core::config('database.default');
        }

        $this->template->content = View::factory('oc-panel/pages/tools/migration',array('db_config'=>$db_config));
    }


    /**
     * does the DB migration
     * @param  pointer $db 
     * @param  string $pf db_prefix
     */
    private function migrate($db,$pf)
    {
        set_time_limit(0);
        
        //oc_accounts --> oc_users
        $users_map = array();
        $accounts = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'accounts`');

        foreach ($accounts as $account) 
        {
            $user = new Model_User();
            $user->name         = $account['name'];
            $user->email        = $account['email'];
            $user->password     = $account['password'];
            $user->created      = $account['createdDate'];
            $user->last_modified= $account['lastModifiedDate'];
            $user->last_login   = $account['lastSigninDate'];
            $user->status       = $account['active'];
            $user->id_role      = 1;
            $user->seoname      = URL::title($user->name, '-', FALSE);
            $user->save();

            $users_map[$account['email']] = $user->id_user;
        }

        //categories --> categories
        $categories_map = array(0=>1);

        $categories = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'categories` ORDER BY `idCategoryParent` ASC');

        foreach ($categories as $category) 
        {
            $cat = new Model_Category();
            $cat->name      = $category['name'];
            $cat->order     = $category['order'];
            $cat->created   = $category['created'];
            $cat->seoname   = $category['friendlyName'];
            $cat->price     = $category['price'];
            $cat->description = $category['description'];
            $cat->parent_deep = ($category['idCategoryParent']>0)? 1:0; //there's only 1 deep
            $cat->id_category_parent = (isset($categories_map[$category['idCategoryParent']]))?$categories_map[$category['idCategoryParent']]:1;
            $cat->save();

            //we save old_id stores the new ID, so later we know the category parent, and to changes the ADS category id
            $categories_map[$category['idCategory']] = $cat->id_category;

        }


        //locations --> locations
        $locations_map = array(0=>1);

        $locations = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'locations` ORDER BY `idLocationParent` ASC');

        foreach ($locations as $location) 
        {
            $loc = new Model_Location();
            $loc->name      = $location['name'];
            $loc->seoname   = $location['friendlyName'];
            $loc->parent_deep = ($location['idLocationParent']>0)? 1:0; //there's only 1 deep
            $loc->id_location_parent = (isset($locations_map[$location['idLocationParent']]))?$locations_map[$location['idLocationParent']]:1;
            $loc->save();

            //we save old_id stores the new ID, so later we know the location parent, and to changes the ADS location id
            $locations_map[$location['idLocation']] = $loc->id_location;

        }

        //posts --> ads
        $ads_map = array();
        $ads = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'posts`');

        foreach ($ads as $a) 
        {
            if (Valid::email($a['email']))
            {
                $ad = new Model_Ad();
                $ad->id_user        = (isset($users_map[$a['email']]))?$users_map[$a['email']]:Model_User::create_email($a['email'], $a['name']);
                $ad->id_category    = (isset($categories_map[$a['idCategory']]))?$categories_map[$a['idCategory']]:1;
                $ad->id_location    = (isset($locations_map[$a['idLocation']]))?$locations_map[$a['idLocation']]:1;
                $ad->title          = $a['title'];
                $ad->seotitle       = URL::title($a['title'], '-', FALSE);
                $ad->description    = (!empty($a['description']))?$a['description']:$a['title'];
                $ad->address        = $a['place'];
                $ad->price          = $a['price'];
                $ad->phone          = $a['phone'];
                $ad->has_images     = $a['hasImages'];
                $ad->ip_address     = ip2long($a['ip']);
                $ad->created        = $a['insertDate'];

                //Status migration...big mess!
                if ($a['isAvailable']==0 AND $a['isConfirmed'] ==0)
                {
                    $ad->status = Model_Ad::STATUS_NOPUBLISHED;
                }
                elseif ($a['isAvailable']==1 AND   $a['isConfirmed'] ==0)
                {
                    $ad->status = Model_Ad::STATUS_NOPUBLISHED;
                }
                elseif ($a['isAvailable']==1 AND   $a['isConfirmed'] ==1)
                {
                    $ad->status = Model_Ad::STATUS_PUBLISHED;
                }
                elseif ($a['isAvailable']==0 AND   $a['isConfirmed'] ==1)
                {
                    $ad->status = Model_Ad::STATUS_UNAVAILABLE;
                }
                elseif ($a['isAvailable']==2 )
                {
                    $ad->status = Model_Ad::STATUS_SPAM;
                }
                else
                {
                    $ad->status = Model_Ad::STATUS_UNAVAILABLE;
                }

                try
                {
                    $ad->save();
                }
                catch (ORM_Validation_Exception $e)
                {
                    d($e->errors(''));
                }

                $ads_map[$a['idPost']] = $ad->id_ad;
                }
            
        }

        //posthits --> visits
        $hits = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'postshits`');

        foreach ($hits as $hit) 
        {
            $visit = new Model_Visit();
            $visit->id_ad       = (isset($ads_map[$hit['idPost']]))?$ads_map[$hit['idPost']]:NULL;
            $visit->created     = $hit['hitTime'];
            $visit->ip_address  = ip2long($hit['ip']);
            $visit->save();
        }

    }


}