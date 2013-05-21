<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Config extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'config';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'config_key';

    
    /**
     * Insert a new object to the database
     * @param  Validation $validation Validation object
     * @return ORM
     */
    public function create(Validation $validation = NULL)
    {
        parent::create($validation);
        $this->reload_config();
    }

    /**
     * Updates a single record or multiple records
     *
     * @chainable
     * @param  Validation $validation Validation object
     * @return ORM
     */
    public function update(Validation $validation = NULL)
    {
        parent::update($validation);
        $this->reload_config();
    }

    /**
     * Deletes a single record while ignoring relationships.
     *
     * @chainable
     * @return ORM
     */
    public function delete()
    {
        parent::delete();
        $this->reload_config();
    }


    public function form_setup($form)
    {
        // $form->fields['group_name']['display_as'] = 'text';
        // $form->fields['config_key']['display_as'] = 'text';
    }

    public function exclude_fields()
    {
        //return array('id_user', 'salt', 'date_created', 'date_lastlogin', 'ip_created', 'ip_lastlogin');
    }

    /**
     * everytime we save the config we relad the cache
     * @return boolean 
     */
    public function reload_config()
    {
        $c = new ConfigDB(); 
        return $c->reload_config();
    }

    protected $_table_columns =    
array (
  'group_name' => 
  array (
    'type' => 'string',
    'column_name' => 'group_name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'character_maximum_length' => '128',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'config_key' => 
  array (
    'type' => 'string',
    'column_name' => 'config_key',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'character_maximum_length' => '128',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'config_value' => 
  array (
    'type' => 'string',
    'character_maximum_length' => '65535',
    'column_name' => 'config_value',
    'column_default' => NULL,
    'data_type' => 'text',
    'is_nullable' => true,
    'ordinal_position' => 3,
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

} // END Model_Config