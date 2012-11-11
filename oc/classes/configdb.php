<?php defined('SYSPATH') or die('No direct script access.');
/**
* Extended functionality for Database Configuration
*
* @package    OC
* @category   Config
* @author     Chema <chema@garridodiaz.com>
* @copyright  (c) 2009-2011 Open Classifieds Team
* @license    GPL v3
*/

class ConfigDB extends Config_Database {
 
    protected $_cache_method;//cache method used
    protected $_cache = NULL;//cache instance
    private static $data;//here we stored the config

    /**
     * construct for oc
     * @param array $config 
     */
    public function __construct(array $config = NULL)
    {
        //most important by construct
        if (isset($config['cache']))
        {
            $this->_cache_method = $config['cache'];
        }
        //if not use the default
        elseif (isset(Kohana::$config->load('cache')->file['driver']))
        {
            $this->_cache_method = Kohana::$config->load('cache')->file['driver'];
        }
        
        $this->_cache = Cache::instance( $this->_cache_method );
        
        //loading the configs in the cache
        $this->load_config();
 
    }

    
    /**
     * loads a group of configs
     * @param type $group
     * @return array 
     */
    public function load($group)
    {                
        //not loaded so try to load, also if devel we refresh cache
        if(self::$data === NULL)
        {
            $this->load_config();
        }
        
        //check if we have it in cache
        if (isset(self::$data[$group]))
        {
            return self::$data[$group];
        }

        //not found
        return FALSE;
    }
    
    /**
     * 
     * Loads the configs from database to the cache
     * @return boolean
     */
    private function load_config()
    {
        //we don't read the config cache in development
        self::$data = (Kohana::$environment===Kohana::DEVELOPMENT)? NULL:$this->_cache->get('config_db');
        
        //only load if empty
        if(self::$data === NULL)
        {
            // Load all the config data to cache
            $query = DB::select('group_name')
                        ->select('config_key')
                        ->select('config_value')
                        ->from($this->_table_name)
                        ->order_by('group_name','asc')
                        ->order_by('config_key','asc')
                        ->execute();
            $configs = $query->as_array();
            foreach($configs as $config)
            {
                self::$data[$config['group_name']][$config['config_key']]=$config['config_value'];
            }
           
            //caching all the results
            $this->_cache->set('config_db', self::$data, 60*60*24);
            
            return TRUE;
        }
        else
        {
            //was already cached
            return FALSE;
        }
        
    }
    
    /**
     * 
     * Clears the config cache and loads it
     * @return boolean
     */
    public function reload_config()
    {
        unset(self::$data);
        // Clears cached data
        $this->_cache->delete('config_db');  
        //load config
        return $this->load_config();
    }
   
    
}//end class