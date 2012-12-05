<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Page extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('title','order');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'page';


	/**
	 *
	 * Loads a basic list info
	 * @param string $view template to render
	 */
	public function action_index($view = NULL)
	{
		parent::action_index('oc-panel/pages/index');
	}
}
