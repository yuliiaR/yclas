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
class Model_Category extends ORM {


	/**
	 * Table name to use
	 *
	 * @access	protected
	 * @var		string	$_table_name default [singular model name]
	 */
	protected $_table_name = 'categories';

	/**
	 * Column to use as primary key
	 *
	 * @access	protected
	 * @var		string	$_primary_key default [id]
	 */
	protected $_primary_key = 'id_category';


	/**
	 * @var  array  ORM Dependency/hirerachy
	 */
	protected $_has_many = array(
		'ads' => array(
			'model'       => 'Ad',
			'foreign_key' => 'id_category',
		),
	);



	/**
	 * Rule definitions for validation
	 *
	 * @return array
	 */
	public function rules()
	{
		return array('id_category'		=> array(array('numeric')),
			        'name'				=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
			        'order'				=> array(),
			        'id_category_parent'=> array(),
			        'parent_deep'		=> array(),
			        'seoname'			=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
			        'description'		=> array(array('max_length', array(':value', 255)), ));
	}

	/**
	 * Label definitions for validation
	 *
	 * @return array
	 */
	public function labels()
	{
		return array('id_category'			=> __('Id'),
			        'name'					=> __('Name'),
			        'order'					=> __('Order'),
			        'created'				=> __('Created'),
			        'id_category_parent'	=> __('Parent'),
			        'parent_deep'			=> __('Parent deep'),
			        'seoname'				=> __('Seoname'),
			        'description'			=> __('Description'),
			        'price'					=> __('Price'));
	}

	/**
	 * 
	 * formmanager definitions
	 * 
	 */
	public function form_setup($form)
	{
		//$form->set_sexclude_fields(array('id_category', 'created'));//, 'seoname'
		$form->fields['password']['display_as'] = 'password';
		// $form->fields['description']['display_as'] = 'textarea';
		// $form->add_field('password_confirm', array('display_as' => 'password'), 'after', 'password');
		// $form->fields['password']['display_as'] = 'password';
		// $form->rule('password_confirm', 'matches', array(':validation', 'password', ':field'));
		// $form->set_value('password', '');
		// $form->set_value('password_confirm', '');
	}

	public function exclude_fields()
	{
	    return array('id_user', 'salt', 'date_created', 'date_lastlogin', 'ip_created', 'ip_lastlogin');
	}


} // END Model_Category