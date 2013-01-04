<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2011 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Visit extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'visits';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_visit';

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
			        'id_visit'	=> array(array('numeric')),
			        'id_ad'	=> array(array('numeric')),
			        'id_user'	=> array(array('numeric')),
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
			        'id_visit'		=> 'Id visit',
			        'id_ad'		=> 'Id ad',
			        'id_user'		=> 'Id user',
			        'created'		=> 'Created',
			        'ip_address'	=> 'Ip address',
			    );
    }



} // END Model_Visit
