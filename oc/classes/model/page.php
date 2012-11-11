<?php defined('SYSPATH') or die('No direct script access.');
/**
 * ORM model page
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2011 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Page extends ORM {


	/**
	 * Table name to use
	 *
	 * @access	protected
	 * @var		string	$_table_name default [singular model name]
	 */
	protected $_table_name = 'pages';

	/**
	 * Column to use as primary key
	 *
	 * @access	protected
	 * @var		string	$_primary_key default [id]
	 */
	protected $_primary_key = 'id_page';




	/**
	 * Rule definitions for validation
	 *
	 * @return array
	 */
	public function rules()
	{
		return array('id_page'		=> array(array('numeric')),
			        'title'				=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
			        'order'				=> array(),
			        'id_page_parent'=> array(),
			        'parent_deep'		=> array(),
			        'seotitle'			=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
			        'text'		=> array());
	}

	/**
	 * Label definitions for validation
	 *
	 * @return array
	 */
	public function labels()
	{
		return array('id_page'	=> 'Id',
			        'title'			=> 'Title',
			        'order'			=> 'Order',
			        'created'		=> 'Created',
			        'id_page_parent'	=> 'Id parent',
			        'parent_deep'	=> 'Parent deep',
			        'seotitle'		=> 'Seo Title',
			        'description'	=> 'Description',
			        );
	}

	/**
	 * 
	 * formo definitions
	 * 
	 * @return array
	 */
	public function formo()
	{
		return array(
                'id_page'		=> array ('render' => FALSE),
    			'created ' 		=> array ('render' => FALSE),
    			'description'	=> array ('driver' => 'textarea'),
		);
	}

	/**
	 * 
	 * formmanager definitions
	 * 
	 */
	public function form_setup($form)
	{
		$form->set_sexclude_fields(array('created'));//, 'seoname'
	}

} // END Model_Category