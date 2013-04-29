<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
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

   /* protected $_belongs_to = array(
        'parent'   => array('model'       => 'Category',
                            'foreign_key' => 'id_category_parent'),
    );*/


	

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
     * we get the categories in an array and a multidimensional array to know the deep
     * @return array 
     */
    public static function get_all()
    {
        $cats = new self;
        $cats = $cats->order_by('order','asc')->find_all()->as_array('id_category');

        //transform the cats to an array
        $cats_arr = array();
        foreach ($cats as $cat) 
        {
            $cats_arr[$cat->id_category] =  array('name' => $cat->name,
                                                    'order' => $cat->order,
                                                    'id_category_parent' => $cat->id_category_parent,
                                                    'parent_deep' => $cat->parent_deep,
                                                );
        }

        //for each category we get his siblings
        $cats_s = array();
        foreach ($cats as $cat) 
             $cats_s[$cat->id_category_parent][] = $cat->id_category;
        

        //last build multidimensional array
        if (count($cats_s)>1)
            $cats_m = self::multi_cats($cats_s);
        else
            $cats_m = array();
        
        return array($cats_arr,$cats_m);
    }

    /**
     * gets a multidimensional array wit the categories
     * @param  array  $cats_s      id_category->array(id_siblings)
     * @param  integer $id_category 
     * @param  integer $deep        
     * @return array               
     */
    public static function multi_cats($cats_s,$id_category = 1, $deep = 0)
    {    
        $ret = NULL;
        //we take all the siblings and try to set the grandsons...
        //we check that the id_category sibling has other siblings
        foreach ($cats_s[$id_category] as $id_sibling) 
        {
            //we check that the id_category sibling has other siblings
            if (isset($cats_s[$id_sibling]))
            {
                if (is_array($cats_s[$id_sibling]))
                {
                    $ret[$id_sibling] = self::multi_cats($cats_s,$id_sibling,$deep+1);
                }
            }
            //no siblings we only set the key
            else 
                $ret[$id_sibling] = NULL;
            
        }
        return $ret;
    }


	/**
	 * 
	 */
	
	public static function category_parent()
	{
		$parent = new self;
		$list = $parent->where('id_category_parent','=',1)->find_all();
		
		$list_parent = array();
		foreach ($list as $l) 
		{
			$list_parent[$l->id_category] = $l->name;	
		}
    //d($list_parent);
		return $list_parent;
	}

	/**
	 * [get_category_children] Array of self children 
	 * @return [array]
	 */
	public function get_category_children()
	{
		$list = new self;
		$categories = $list->find_all();
				
		$children_categ = NULL;
		foreach ($categories as $c) 
		{
            $ads = new Model_Ad();
			$count = $ads->where('id_category', '=', $c->id_category)
                    ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                    ->count_all();

			if($c->id_category != $c->id_category_parent)
			{	
				$children_categ[$c->id_category] = array('id_category'	=> $c->id_category,
														'name'			=> $c->seoname,
														'parent'		=> $c->id_category_parent,
														'parent_deep'	=> $c->parent_deep,
														'order'			=> $c->order,
														'price'			=> $c->price,
														'count'			=> $count
														);
			}
		}
		
		return $children_categ;
	}


	
	/**
	 * 
	 * formmanager definitions
	 * 
	 */
	public function form_setup($form)
	{	
		 
		$form->fields['description']['display_as'] = 'textarea';

        $form->fields['id_category_parent']['display_as'] = 'hidden';
        $form->fields['id_category_parent']['value'] = 1;

		/*$form->fields['price']['caption'] = 'currency';
	
		$form->fields['parent_deep']['display_as'] = 'select';
		$form->fields['parent_deep']['options'] = range(0,2);


        $form->fields['id_category_parent']['dont_reindex_options']  = true;
		$form->fields['id_category_parent']['display_as']     = 'select';
		$form->fields['id_category_parent']['options']        = self::category_parent();// range(1,10);


		$form->fields['order']['display_as'] = 'select';
		$form->fields['order']['options'] = range(0,30);*/
		
	}

	public function exclude_fields()
	{
		return array('created','parent_deep','order');
	}


    protected $_table_columns =  
array (
  'id_category' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'name' => 
  array (
    'type' => 'string',
    'column_name' => 'name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'order' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'order',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'display' => '2',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'created' => 
  array (
    'type' => 'string',
    'column_name' => 'created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'comment' => '',
    'extra' => 'on update CURRENT_TIMESTAMP',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_category_parent' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category_parent',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'parent_deep' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'parent_deep',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'display' => '2',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'seoname' => 
  array (
    'type' => 'string',
    'column_name' => 'seoname',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 7,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'description' => 
  array (
    'type' => 'string',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 8,
    'character_maximum_length' => '255',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'price' => 
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'price',
    'column_default' => '0',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'numeric_scale' => '0',
    'numeric_precision' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

} // END Model_Category