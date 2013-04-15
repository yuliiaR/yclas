<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2011 Open Classifieds Team
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
        $this->reload_config();
        parent::create($validation);
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
        $this->reload_config();
        parent::update($validation);
    }

    /**
     * Deletes a single record while ignoring relationships.
     *
     * @chainable
     * @return ORM
     */
    public function delete()
    {
        $this->reload_config();
        parent::delete();
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


} // END Model_Config