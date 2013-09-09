<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Model For Custom Fields, handles altering the table and the configs were we save extra data.
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Field {

    private $_db_prefix = NULL; //db prefix
    private $_db        = NULL; //db instance
    private $_bs        = NULL; //blacksmith module instance
    private $_name_prefix = 'cf_'; //prefix used in front of the column name

    public function __construct()
    {
        $this->_db_prefix   = core::config('database.default.table_prefix');
        $this->_db          = Database::instance();
        $this->_bs          = Blacksmith::alter();

    }


    public function get_all()
    {

    }

    public function create($name, $type = 'string', $length = NULL, $default = NULL)
    {
        if (!$this->field_exists($name))
        {

            $table = $this->_bs->table($this->_db_prefix.'ads');

            switch ($type) 
            {
                case 'int':
                    # code...
                    break;
                case 'string':            
                default:
                    $table->add_column()
                        ->string($this->_name_prefix.$name, $length)
                        ->default_value($default);
                    break;
            }
            

            $this->_bs->forge($this->_db);


            Cache::instance()->delete_all();
            Theme::delete_minified();

            Alert::set(Alert::SUCCESS,__('Field created '.$name));
        }
        else
            Alert::set(Alert::ERROR,__('Field already exists '.$name));

    }

    public function delete($name)
    {
        
        if ($this->field_exists($name))
        {
            $table = $this->_bs->table($this->_db_prefix.'ads');
            $table->drop_column($this->_name_prefix.$name);
            $this->_bs->forge($this->_db);


            Cache::instance()->delete_all();
            Theme::delete_minified();
            Alert::set(Alert::SUCCESS,__('Field deleted '.$name));
        }
        else
            Alert::set(Alert::ERROR,__('Field does not exists '.$name));

        
    }

    /**
     * get the custom fields for an ad
     * @return array
     */
    public static function get_ad_fields($id_ad)
    {
        
    }

    /**
     * says if a field exists int he table ads
     * @param  string $name 
     * @return bool      
     */
    private function field_exists($name)
    {
        $columns = Database::instance()->list_columns('ads');
        return (array_key_exists($this->_name_prefix.$name, $columns));
    }



}