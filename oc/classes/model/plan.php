<?php defined('SYSPATH') or die('No direct script access.');
/**
 * plan for memberships
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Plan extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'plans';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_plan';

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
			        'price'     => array(array('price')),
                    'days'      => array(array('numeric'),array('range',array(':value',1,10000000000))),
                    'amount_ads'=> array(array('numeric'),array('range',array(':value',1,10000000000))),
                    'seoname'   => array(   array(array($this, 'unique'), array('seoname', ':value')),
                                            array('not_empty'),
                                            array('max_length', array(':value', 145)), 
                                    ),
                    'name'      => array(   array('not_empty'),
                                            array('max_length', array(':value', 145)), 
                                    ),
			    );
    }

    public function exclude_fields()
    {
        return array('created');
    }

/// protected $_table_columns =  TODO!!


} // END Model_Plan