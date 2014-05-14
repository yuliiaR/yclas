<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@open-classifieds.com>
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


    protected $_belongs_to = array(
        'parent'   => array('model'       => 'Location',
                            'foreign_key' => 'id_location_parent'),
    );

    
    /**
     * global Model Location instance get from controller so we can access from anywhere like Model_Location::current()
     * @var Model_Location
     */
    protected static $_current = NULL;

    /**
     * returns the current location
     * @return Model_Location
     */
    public static function current()
    {
        //we don't have so let's retrieve
        if (self::$_current === NULL)
        {
            self::$_current = new self();
            if(Request::current()->param('location') != NULL || Request::current()->param('location') != URL::title(__('all')))
            {
                self::$_current = self::$_current->where('seoname', '=', Request::current()->param('location'))
                                                    ->limit(1)->cached()->find();
            }
        }

        return self::$_current;
    }
        
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
	        'name'					=> __('Name'),
	        'id_location_parent'	=> __('Parent'),
	        'parent_deep'			=> __('Parent deep'),
	        'seoname'				=> __('Seoname'),
	        'description'			=> __('Description'),
		);
	}

    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters()
    {
        return array(
                'seoname' => array(
                                array(array($this, 'gen_seoname'))
                              ),
                'id_location_parent' => array(
                                array(array($this, 'check_parent'))
                              ),
        );
    }

    /**
     * we get the locations in an array and a multidimensional array to know the deep
     * @return array 
     */
    public static function get_all()
    {
        $locs = new self;
        $locs = $locs->order_by('order','asc')->find_all()->cached()->as_array('id_location');

        //transform the locs to an array
        $locs_arr = array();
        foreach ($locs as $loc) 
        {
            $locs_arr[$loc->id_location] =  array('name'               => $loc->name,
                                                  'order'              => $loc->order,
                                                  'id_location_parent' => $loc->id_location_parent,
                                                  'parent_deep'        => $loc->parent_deep,
                                                  'seoname'            => $loc->seoname,
                                                  'id'                 => $loc->id_location,
                                                );
        }

        // array by parent deep, 
        // each parent deep is one array with categories of the same index
        $locs_parent_deep = array();
        foreach ($locs as $loc) 
        {
            $locs_parent_deep[$loc->parent_deep][$loc->id_location] =  array('name'               => $loc->name,
                                                                              'id_location_parent' => $loc->id_location_parent,
                                                                              'parent_deep'        => $loc->parent_deep,
                                                                              'seoname'            => $loc->seoname,
                                                                              'id'                 => $loc->id_location,
                                                                    );
        }
        //sort by key, in case lover level is befor higher
        ksort($locs_parent_deep);

        //for each location we get his siblings
        $locs_s = array();
        foreach ($locs as $loc) 
             $locs_s[$loc->id_location_parent][] = $loc->id_location;
            

        //last build multidimensional array
        if (count($locs_s)>1)
            $locs_m = self::multi_locs($locs_s);
        else
            $locs_m = array();

        return array($locs_arr,$locs_m, $locs_parent_deep);
    }

    /**
     * we get the locations in an array and a multidimensional array to know the deep
     * @param  int ID of location 
     * @param  string needed attribute to be returned
     * @return string   
     */
    
    public static function get_location($id, $attr)
    {
      $location = new self($id);
      return $location->$attr;
    }

    /**
   * counts the location ads
   * @return array
   */
    public static function get_location_count()
    {
          $expr_date = core::config('advertisement.expire_date');
          $locs = DB::select('l.*')
                  ->select(array(DB::select('COUNT("id_ad")')
                          ->from(array('ads','a'))
                          ->where('a.id_location','=',DB::expr(core::config('database.default.table_prefix').'l.id_location'))
                          ->where(DB::expr('IF('.$expr_date.' <> 0, DATE_ADD( published, INTERVAL '.$expr_date.' DAY), DATE_ADD( NOW(), INTERVAL 1 DAY))'), '>', DB::expr('NOW()'))
                          ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                          ->group_by('id_location'), 'count'))
                  ->from(array('locations', 'l'))
                  ->order_by('order','asc')
                  ->as_object()
                  ->cached()
                  ->execute();

          $locs_count = array();
          $parent_count = array();

          foreach ($locs as $l) 
          {
              $locs_count[$l->id_location] = array('id_location'          => $l->id_location,
                                                    'seoname'             => $l->seoname,
                                                    'name'                => $l->name,
                                                    'id_location_parent'  => $l->id_location_parent,
                                                    'parent_deep'         => $l->parent_deep,
                                                    'order'               => $l->order,
                                                    'has_siblings'        => FALSE,
                                                    'count'               => (is_numeric($l->count))?$l->count:0
                                                    );
              //counting the ads the parent have
              if ($l->id_location_parent!=0)
              {
                  if (!isset($parent_count[$l->id_location_parent]))
                      $parent_count[$l->id_location_parent] = 0;

                  $parent_count[$l->id_location_parent]+=$l->count;
              }
              
          }

          foreach ($parent_count as $id_location => $count) 
          {
              //attaching the count to the parents so we know each parent how many ads have
              $locs_count[$id_location]['count'] += $count;
              $locs_count[$id_location]['has_siblings'] = TRUE;
          }
              
      
      return $locs_count;
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
        if (isset($locs_s[$id_location]))
        {
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
        }
        
        return $ret;
    }

	public function form_setup($form)
	{
		$form->fields['description']['display_as'] = 'textarea';

        $form->fields['id_location_parent']['display_as']   = 'select';
        $form->fields['id_location_parent']['caption']      = 'name';   

        $form->fields['order']['display_as']   = 'select';
        $form->fields['order']['options']      = range(1, 100);

        // $form->fields['id_location_parent']['display_as'] = 'hidden';
        // $form->fields['parent_deep']['display_as'] = 'hidden';
        // $form->fields['order']['display_as'] = 'hidden';
	}

	public function exclude_fields()
	{
	  return array('created','parent_deep');
	}

    /**
     * returns all the siblings ids+ the idlocation, used to filter the ads
     * @return array
     */
    public function get_siblings_ids()
    {
        $locs = array();

        if ($this->loaded())
        {
            $locs[] = $this->id_location;

            $cat_ids = new self();
            $cat_ids = $cat_ids->where('id_location_parent','=',$this->id_location)->cached()->find_all();

            foreach ($cat_ids as $c) 
            {
                $locs[] = $c->id_location;
            }
        }

        return $locs;
    }


    /**
     * return the title formatted for the URL
     *
     * @param  string $title
     * 
     */
    public function gen_seoname($seoname)
    {
        //in case seoname is really small or null
        if (strlen($seoname)<3)
            $seoname = $this->name;

        $seoname = URL::title($seoname);
        if ($seoname != $this->seoname)
        {
            $loc = new self;
            //find a user same seoname
            $s = $loc->where('seoname', '=', $seoname)->limit(1)->find();

            //found, increment the last digit of the seoname
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seoname.'-'.$cont;
                    $loc = new self;
                    unset($s);
                    $s = $loc->where('seoname', '=', $attempt)->limit(1)->find();
                    if(!$s->loaded())
                    {
                        $loop = FALSE;
                        $seoname = $attempt;
                    }
                    else
                  {
                        $cont++;
                    }
                }
            }
        }

        return $seoname;
    }

    /**
     * returns the deep of parents of this location
     * @return integer
     */
    public function get_deep()
    {
        //initial deep
        $deep = 0;

        if ($this->loaded())
        {
            //getting all the cats as array
            list($locs_arr,$locs_m) = Model_Location::get_all();

            //getin the parent of this location
            $id_location_parent = $locs_arr[$this->id_location]['id_location_parent'];

            //counting till we find the begining
            while ($id_location_parent != 1 AND $id_location_parent != 0 AND $deep<100) 
            {
                $id_location_parent = $locs_arr[$id_location_parent]['id_location_parent'];
                $deep++;
            }
        }
        
        return $deep;
    }

    /**
     * rule to verify that we selected a parent if not put the root location
     * @param  integer $id_parent 
     * @return integer                     
     */
    public function check_parent($id_parent)
    {
        return (is_numeric($id_parent))? $id_parent:1;
    }


    protected $_table_columns =  
array (
  'id_location' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location',
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
    'character_maximum_length' => '64',
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
  'id_location_parent' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location_parent',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 4,
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
    'ordinal_position' => 5,
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
    'ordinal_position' => 6,
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
    'ordinal_position' => 7,
    'character_maximum_length' => '255',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);
} // END Model_Location
