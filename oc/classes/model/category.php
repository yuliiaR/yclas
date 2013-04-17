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
        $cats_m = self::multi_cats($cats_s);

        return array($cats_arr,$cats_m);
    }

    public static function multi_cats($cats_s,$id_category = 1, $deep = 0)
    {   
        $log = FALSE;

        if ($log) for ($i=0, $deep_c=''; $i < $deep; $i++) $deep_c.='----';
        if ($log) echo $deep_c.'id_category:'.$id_category.' deep:'.$deep.'<br>';
        if ($log) echo $deep_c.'siblings:'.print_r($cats_s[$id_category],1).'<br>';
        $ret = NULL;

        //we take all the siblings and try to set the grandsons...
        //we check that the id_category sibling has other siblings
        foreach ($cats_s[$id_category] as $id_sibling) 
        {
            if ($log) echo $deep_c.'sibling id_category:'.$id_sibling.'<br>';

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
        
        if ($log) echo $deep_c.'id_category:'.$id_category.' end multi<br><br>';
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
		
		$ads = new Model_Ad();
		
		$children_categ = NULL;
		foreach ($categories as $c) 
		{
			$count = $ads->where('id_category', '=', $c->id_category)->count_all();

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


} // END Model_Category