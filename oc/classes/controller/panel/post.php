 <?php defined('SYSPATH') or die('No direct script access.');

 class Controller_Panel_Post extends Auth_Crud {

 	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('seotitle','price');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'post';



 } // End Post controller
