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
class Model_Location extends ORM {

	/**
	 * Table name to use
	 *
	 * @access	protected
	 * @var		string	$_table_name default [singular model name]
	 */
	protected $_table_name = 'locations';

	/**
	 * Column to use as primary key
	 *
	 * @access	protected
	 * @var		string	$_primary_key default [id]
	 */
	protected $_primary_key = 'id_location';


	/**
	 * Rule definitions for validation
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
				        'id_location'		=> array(array('numeric')),
				        'name'				=> array(array('not_empty'), array('max_length', array(':value', 64)), ),
				        'id_location_parent'=> array(),
				        'parent_deep'		=> array(),
				        'seoname'			=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
				        'description'		=> array(array('max_length', array(':value', 255)), ),
		);
	}

	/**
	 * Label definitions for validation
	 *
	 * @return array
	 */
	public function labels()
	{
		return  array(
	        'id_location'			=> 'Id',
	        'name'					=> 'Name',
	        'id_location_parent'	=> 'Id parent',
	        'parent_deep'			=> 'Parent deep',
	        'seoname'				=> 'Seoname',
	        'description'			=> 'Description',
	        'lat'					=> 'Lat',
	        'lng'					=> 'Lng',
		);
	}

    /**
     * we get the locations in an array and a multidimensional array to know the deep
     * @return array 
     */
    public static function get_all()
    {
        $locs = new self;
        $locs = $locs->order_by('order','asc')->find_all()->as_array('id_location');

        //transform the locs to an array
        $locs_arr = array();
        foreach ($locs as $loc) 
        {
            $locs_arr[$loc->id_location] =  array('name' => $loc->name,
                                                    'order' => $loc->order,
                                                    'id_location_parent' => $loc->id_location_parent,
                                                    'parent_deep' => $loc->parent_deep,
                                                );
        }

        //for each location we get his siblings
        $locs_s = array();
        foreach ($locs as $loc) 
             $locs_s[$loc->id_location_parent][] = $loc->id_location;
            

        //last build multidimensional array
        if (count($locs_s)>1)
            $locs_m = self::multi_locs($locs_s);
        else
            $locs_m = array();

        return array($locs_arr,$locs_m);
    }

    /**
     * gets a multidimensional array wit the locations
     * @param  array  $locs_s      id_location->array(id_siblings)
     * @param  integer $id_location 
     * @param  integer $deep        
     * @return array               
     */
    public static function multi_locs($locs_s,$id_location = 1, $deep = 0)
    {    
        $ret = NULL;
        //we take all the siblings and try to set the grandsons...
        //we check that the id_location sibling has other siblings
        foreach ($locs_s[$id_location] as $id_sibling) 
        {
            //we check that the id_location sibling has other siblings
            if (isset($locs_s[$id_sibling]))
            {
                if (is_array($locs_s[$id_sibling]))
                {
                    $ret[$id_sibling] = self::multi_locs($locs_s,$id_sibling,$deep+1);
                }
            }
            //no siblings we only set the key
            else 
                $ret[$id_sibling] = NULL;
            
        }
        return $ret;
    }

	public function form_setup($form)
	{
		$form->fields['description']['display_as'] = 'textarea';
	

		$form->fields['seoname']['caption'] = 'seoname';

        $form->fields['id_location_parent']['display_as'] = 'hidden';
        $form->fields['id_location_parent']['value'] = 1;
				
	}

	public function exclude_fields()
	{
	  return array('created','parent_deep','order');
	}



} // END Model_Location