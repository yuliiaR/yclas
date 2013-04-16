<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2011 Open Classifieds Team
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
			        'id_ad'		=> __('Id ad'),
			        'id_user'		=> __('Id user'),
			        'id_category'	=> __('Id category'),
			        'id_location'	=> __('Id location'),
			        'type'			=> __('Type'),
			        'title'			=> __('Title'),
			        'seotitle'		=> __('SEO title'),
			        'description'	=> __('Description'),
			        'address'		=> __('address'),
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
    public function count_ad_hit($id_ad, $id_user){
        
        //inser new table, as a hit
        $new_hit = DB::insert('visits', array('id_ad', 'id_user'))
                                ->values(array($id_ad, $id_user))
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
    public function save_image($image, $id, $created, $seotitle)
    {
        $counter = 0;
        foreach ($image as $image) 
        { $counter++;
         
            if ( 
            ! Upload::valid($image) OR
            ! Upload::not_empty($image) OR
            ! Upload::type($image, array('jpg', 'jpeg', 'png')) OR
            ! Upload::size($image, core::config('image.max_image_size').'M'))
            {
                if ( Upload::not_empty($image) && ! Upload::type($image, array('jpg', 'jpeg', 'png')))
                {
                    Alert::set(Alert::ALERT, __($image['name'].' Is not valid format, please use one of this formats "jpg, jpeg, png"'));
                    return array("error"=>FALSE, "error_name"=>"wrong_format");
                } 
                if(!Upload::size($image, core::config('general.max_image_size').'M'))
                {
                    Alert::set(Alert::ALERT, __($image['name'].' Is not of valid size. Size is limited on '.core::config('general.max_image_size').'MB per image'));
                    return array("error"=>FALSE, "error_name"=>"wrong_format");
                }
                return array("error"=>FALSE, "error_name"=>"no_image");
            }
            
            if ($image !== NULL)
            {
                $path = $this->image_path($id , $created);
                $directory = DOCROOT.$path;
                $width = core::config('image.width');
                $height = core::config('image.height');
                $width_thumb = core::config('image.width_thumb');
                $height_thumb = core::config('image.height_thumb');


                if ($file = Upload::save($image, NULL, $directory))
                {
                    $name = strtolower(Text::random('alnum',20));
                    $filename_thumb = 'thumb_'.$seotitle.'_'.$counter.'.jpg';
                    $filename_original = $seotitle.'_'.$counter.'.jpg';
                    $filename_crop = $name.'_crop.jpg';
                    $image_size_orig = getimagesize($file);
                    
                     if($image_size_orig[0] >= $width)
                    {
                        Image::factory($file)
                            ->resize($width, NULL, Image::AUTO)
                            ->save($directory.$filename_original);    
                    }
                    else
                    {
                         Image::factory($file)
                            ->save($directory.$filename_original); 
                    }


                    Image::factory($directory.$filename_original)->crop($width_thumb, $height_thumb)->save($directory.$filename_crop);
                    Image::factory($file)
                        ->resize(200, 200, Image::NONE)
                        ->save($directory.$filename_thumb);
                    
                   
                    
                    
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

} // END Model_ad
