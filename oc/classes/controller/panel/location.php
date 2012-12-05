<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Location extends Auth_Crud {



	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_location','name','id_location_parent');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'location';




}
