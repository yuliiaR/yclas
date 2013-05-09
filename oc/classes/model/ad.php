<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * 
 */
class Model_Ad extends ORM {

    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'ads';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id_ad]
     */
    protected $_primary_key = 'id_ad';

    protected $_belongs_to = array(
        'user'		 => array('foreign_key' => 'id_user'),
        'category'	 => array('foreign_key' => 'id_category'),
    	'location'	 => array('foreign_key' => 'id_location'),
    );

    /**
     * status constants
     */
    const STATUS_NOPUBLISHED = 0; //first status of the item, not published
    const STATUS_PUBLISHED   = 1; // ad it's available and published
    const STATUS_SPAM        = 30; // mark as spam
    const STATUS_UNAVAILABLE = 50; // item unavailable but previously was
    
    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
				        'id_ad'		=> array(array('numeric')),
				        'id_user'		=> array(array('numeric')),
				        'id_category'	=> array(array('numeric')),
				        'id_location'	=> array(),
				        'type'			=> array(),
				        'title'			=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
				        'seotitle'		=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
				        'description'	=> array(array('not_empty'), array('max_length', array(':value', 65535)), ),
				        'address'		=> array(array('max_length', array(':value', 145)), ),
				        'phone'			=> array(array('max_length', array(':value', 30)), ),
				        'status'		=> array(array('numeric')),
				        'has_images'	=> array(array('numeric')),
				    );
    }

    /**
     * Label definitions for validation
     *
     * @return array
     */
    public function labels()
    {
    	return array(
			        'id_ad'		=> 'Id ad',
			        'id_user'		=> __('User'),
			        'id_category'	=> __('Category'),
			        'id_location'	=> __('Location'),
			        'type'			=> __('Type'),
			        'title'			=> __('Title'),
			        'seotitle'		=> __('SEO title'),
			        'description'	=> __('Description'),
			        'address'		=> __('Address'),
			        'price'			=> __('Price'),
			        'phone'			=> __('Phone'),
			        'ip_address'	=> __('Ip address'),
			        'created'		=> __('Created'),
			        'published'		=> __('Published'),
			        'status'		=> __('Status'),
			        'has_images'	=> __('Has images'),
			    );
    }
    
    /**
     * 
     * formmanager definitions
     * @param $form
     * @return $insert
     */
    public function form_setup($form)
    {
        $insert = DB::insert('ads', array('title', 'description'))
                            ->values(array($form['title'], $form['description']))
                            ->execute();
                            return $insert;
    }


    /**
     * generate seo title. return the title formatted for the URL
     *
     * @param string title
     * @return $seotitle (unique string)  
     */
    
    public function gen_seo_title($title)
    {

        $ad = new self;

        $title = URL::title($title, '-', FALSE);
        $seotitle = $title;

        //find a ad same seotitle
        $a = $ad->where('seotitle', '=', $seotitle)->and_where('id_ad', '!=', $this->id_ad)->limit(1)->find();
        
        if($a->loaded())
        {
            $cont = 1;
            $loop = TRUE;
            do {
                $attempt = $title.'-'.$cont;
                $ad = new self;
                unset($a);
                $a = $ad->where('seotitle', '=', $attempt)->limit(1)->find();

                if(!$a->loaded())
                {
                    $loop = FALSE;
                    $seotitle = $attempt;
                }
                else $cont++;
            } while ( $loop );
        }

        return $seotitle;
    }

   


    /**
     *
     *  Create single table for each advertisement hit
     *  
     *  @param $id_ad (model_ad), $id_user(model_user) 
     */
    public function count_ad_hit($id_ad, $visitor_id, $ip_address){
        
        //inser new table, as a hit
        $new_hit = DB::insert('visits', array('id_ad', 'id_user', 'ip_address'))
                                ->values(array($id_ad, $visitor_id, $ip_address))
                                ->execute();

    }

    /**
     * [gen_img_path] Generate image path with a given parameters $seotitke and 
     * date of advertisement cration 
     * @param  [string] $id         [id of advert ]
     * @param  [date]   $created     [date of creation]
     * @return [string]             [directory path]
     */
    public function gen_img_path($id, $created)
    { 
        
        $obj_date = date_parse($created); // convert date to array 
        
            $year = $obj_date['year']; // take last 2 integers 
        
        // check for length, because original path is with 2 integers 
        if(strlen($obj_date['month']) != 2)
            $month = '0'.$obj_date['month'];
        else
            $month = $obj_date['month'];
        
        if(strlen($obj_date['day']) != 2)
            $day = '0'.$obj_date['day'];
        else
            $day = $obj_date['day'];

        $directory = 'images/'.$year.'/'.$month.'/'.$day.'/'.$id.'/';
       
        return $directory;
    }

    /**
     * save_image upload images with given path
     * 
     * @param  [array]  $image      [image $_FILE-s ]
     * @param  [string] $seotitle   [unique id, and folder name]
     * @return [bool]               [return true if 1 or more images uploaded, false otherwise]
     */
    public function save_image($images, $id, $created, $seotitle)
    {
        
        foreach ($images as $image) 
        { 
         
            if ( 
            ! Upload::valid($image) OR
            ! Upload::not_empty($image) OR
            ! Upload::type($image, array('jpg', 'jpeg', 'png')) OR
            ! Upload::size($image, core::config('image.max_image_size').'M'))
            {
                if ( Upload::not_empty($image) && ! Upload::type($image, array(core::config('image.allowed_formats'))))
                {
                    Alert::set(Alert::ALERT, $image['name'].' '.__('Is not valid format, please use one of this formats "jpg, jpeg, png"'));
                    return array("error"=>FALSE, "error_name"=>"wrong_format");
                } 
                if(!Upload::size($image, core::config('general.max_image_size').'M'))
                {
                    Alert::set(Alert::ALERT, $image['name'].' '.__('Is not of valid size. Size is limited on '.core::config('general.max_image_size').'MB per image'));
                    return array("error"=>FALSE, "error_name"=>"wrong_format");
                }
                return array("error"=>FALSE, "error_name"=>"no_image");
            }
            

            if ($image !== NULL)
            {
                $path           = $this->image_path($id , $created);
                $directory      = DOCROOT.$path;
                $image_quality  = core::config('image.quality');
                $width          = core::config('image.width');
                $width_thumb    = core::config('image.width_thumb');
                $height_thumb   = core::config('image.height_thumb');
                $height         = core::config('image.height');

                if(!is_numeric($height)) // when installing this field is empty, to avoid crash we check here
                    $height         = NULL;
                if(!is_numeric($height_thumb))
                    $height_thumb   = NULL;    
                
                // d($height_thumb);
                // count howmany files are saved 
                if (glob($directory . "*.jpg") != false)
                {
                    $filecount = count(glob($directory . "*.jpg"));

                    $counter = ($filecount / 2) + 1;
                    
                    if(file_exists($directory.$seotitle.'_'.$counter.'.jpg')) // in case we update image, we have to find available number to replace
                    {
                        for($i=1; $i<=core::config('advertisement.num_images'); $i++)
                        {
                            $counter = $i;
                            if(!file_exists($directory.$seotitle.'_'.$counter.'.jpg'))
                            {
                                break;
                            }
                        }
                    }
                    
                }
                else
                    $counter = 1;
                
                
                if ($file = Upload::save($image, NULL, $directory))
                {
                    $filename_thumb     = 'thumb_'.$seotitle.'_'.$counter.'.jpg';
                    $filename_original  = $seotitle.'_'.$counter.'.jpg';
                    
                    //if original image is bigger that our constants we resize
                    $image_size_orig    = getimagesize($file);
                    if($image_size_orig[0] > $width || $image_size_orig[1] > $height)
                    {
                        Image::factory($file)
                            ->resize($width, $height, Image::AUTO)
                            ->save($directory.$filename_original,$image_quality);    
                    }
                    //we just save the image changing the quality and different name
                    else
                        Image::factory($file)
                            ->save($directory.$filename_original,$image_quality); 
                    

                    //creating the thumb and resizing using the the biggest side INVERSE
                    Image::factory($directory.$filename_original)
                        ->resize($width_thumb, $height_thumb, Image::INVERSE)
                        ->save($directory.$filename_thumb,$image_quality);

                    //check if the height or width of the thumb is bigger than default then crop
                    if ($height_thumb!==NULL)
                    {
                        $image_size_orig = getimagesize($directory.$filename_thumb);
                        if ($image_size_orig[1] > $height_thumb || $image_size_orig[0] > $width_thumb)
                        Image::factory($directory.$filename_thumb)
                                    ->crop($width_thumb, $height_thumb)
                                    ->save($directory.$filename_thumb); 
                    }
                   

                    
                    // Delete the temporary file
                    unlink($file);

                    return array("error"=>TRUE, "error_name"=>NULL);
                }
                else
                { 
                    return array("error"=>FALSE, "error_name"=>"upload_unsuccessful");
                }
            }   
        }
        return array("error"=>FALSE, "error_name"=>"no_image");
    }

    /**
     * image_path make unique dir path with a given date and id
     * 
     * @param  [int] $id   [unique id, and folder name]
     * @return [string]             [directory path]
     */
    public function image_path($id, $created)
    { 
        if ($created !== NULL)
        {
            $obj_ad = new Model_Ad();
            $path = $obj_ad->gen_img_path($id, $created);
        }
        else
        {
            $date = Date::format(time(), 'Y/m/d');

            $parse_data = explode("/", $date);          // make array with date values
        
            $path = "images/"; // root upload folder

            for ($i=0; $i < count($parse_data); $i++) 
            { 
                $path .= $parse_data[$i].'/';           // append, to create path 
                
            }
                $path .= $id.'/';
        }
        
        

        if(!is_dir($path)){         // check if path exists 
                mkdir($path, 0755, TRUE);
            }

        return $path;
    }

    public function delete_images($img_path)
    {
        // Loop through the folder
        $dir = dir($img_path);

        while (false !== $entry = $dir->read()) {
        // Skip pointers
          if ($entry == '.' || $entry == '..') {
            continue;
          }
          unlink($img_path.$entry);
        }
        
        rmdir($img_path);
        return TRUE;
    }
    
    protected $_table_columns =     
array (
  'id_ad' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_ad',
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
  'id_user' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_user',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'id_category' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'id_location' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location',
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
  'type' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '255',
    'column_name' => 'type',
    'column_default' => '0',
    'data_type' => 'tinyint unsigned',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'title' => 
  array (
    'type' => 'string',
    'column_name' => 'title',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'seotitle' => 
  array (
    'type' => 'string',
    'column_name' => 'seotitle',
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
    'character_maximum_length' => '65535',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'text',
    'is_nullable' => false,
    'ordinal_position' => 8,
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'address' => 
  array (
    'type' => 'string',
    'column_name' => 'address',
    'column_default' => '0',
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 9,
    'character_maximum_length' => '145',
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
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'numeric_scale' => '3',
    'numeric_precision' => '14',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'phone' => 
  array (
    'type' => 'string',
    'column_name' => 'phone',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 11,
    'character_maximum_length' => '30',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'website' => 
  array (
    'type' => 'string',
    'column_name' => 'website',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 12,
    'character_maximum_length' => '200',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'ip_address' => 
  array (
    'type' => 'float',
    'column_name' => 'ip_address',
    'column_default' => NULL,
    'data_type' => 'float',
    'is_nullable' => true,
    'ordinal_position' => 13,
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
    'ordinal_position' => 14,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'published' => 
  array (
    'type' => 'string',
    'column_name' => 'published',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 15,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'featured' => 
  array (
    'type' => 'string',
    'column_name' => 'featured',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 16,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'status' => 
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'status',
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 17,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'has_images' => 
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'has_images',
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 18,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);
} // END Model_ad
