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
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
			       
			    );
    }

    /**
     * Label definitions for validation
     *
     * @return array
     */
    public function labels()
    {
    	return array(
			       
			    );
    }

    public function form_setup($form)
    {
        $form->fields['password']['display_as'] = 'password';
    }

    public function exclude_fields()
    {
        return array('id_user', 'salt', 'date_created', 'date_lastlogin', 'ip_created', 'ip_lastlogin');
    }



} // END Model_Config