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
			        'description'		=> array(array('max_length', array(':value', 255)), ),
			        'price'				=> array(array('numeric')), );
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
		// get values from form form config file 
		$config = Kohana::$config->load('form');
		$general = $config->get('general');
		$category = $config->get('category'); 

		if ($general['description']) 
			$form->fields['description']['display_as'] = 'textarea';
		if($category['price']) 
			$form->fields['price']['caption'] = 'currency';
		$form->fields['order']['display_as'] = 'select';
		$form->fields['order']['options'] = range(0,30);
		if ($general['parent_deep']) 
		{
			$form->fields['parent_deep']['display_as'] = 'select';
			$form->fields['parent_deep']['options'] = range(0,3);
		}
	}

	public function exclude_fields()
	{
		// get values from form form config file 
		$config = Kohana::$config->load('form');
		$general = $config->get('general');
		$category = $config->get('category'); 
		
		$res = array();
		foreach ($general as $g => $value) 
		{
			if($value == FALSE)
			{
				array_push($res, $g);
			}
		}
		foreach($category as $c => $value)
		{
			if($value == FALSE)
			{
				array_push($res, $c);
			}
		}
	    return $res;
	}


} // END Model_Category