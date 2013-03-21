<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Migration class from 1.7.x and higher to 2.0.
 *
 * @package    OC
 * @category   Migration
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Migration extends Auth_Controller {

    public function action_index()
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

        $autorefresh->template->title   = __('Open Classifieds migration');
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

        $this->template->content = View::factory('oc-panel/migration',array('db_config'=>$db_config));
    }



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
            $user->seoname      = $user->gen_seo_title($user->name);
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
                $ad->seotitle       = $ad->gen_seo_title($a['title']);
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