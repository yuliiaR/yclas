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

        //form where information was previously stored 
        //populate form from current config to help a bit
        //on submit 
        // connect to new mysql connection
        // create config group migration to store in which ID was stuck (if happens)
        // do migration using iframe autorefresh?

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
        $accounts = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'accounts`');

        //oc_accounts --> oc_users
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
        }

        die();
    }


}