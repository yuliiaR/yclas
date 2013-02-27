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
				        'adress'		=> array(array('max_length', array(':value', 145)), ),
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
			        'adress'		=> __('Adress'),
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

        $title = $ad->gen_to_seo($title);
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

    public function gen_to_seo($to_seo)
    {

        $seoname = preg_replace('/\%/',' percentage',$to_seo);
        $seoname = preg_replace('/\@/',' at ',$seoname);
        $seoname = preg_replace('/\&/',' and ',$seoname);
        $seoname = preg_replace('/\s[\s]+/','-',$seoname);    // Strip off multiple spaces
        $seoname = preg_replace('/[\s\W]+/','-',$seoname);    // Strip off spaces and non-alpha-numeric
        $seoname = preg_replace('/^[\-]+/','',$seoname); // Strip off the starting hyphens
        $seoname = preg_replace('/[\-]+$/','',$seoname); // // Strip off the ending hyphens
        $seoname = strtolower($seoname);

        return $seoname;
    }

    /**
     * confirm payment for order
     *
     * @param string    $id_order [unique indentifier of order]
     * @param int       $id_user  [unique indentifier of user] 
     */
    public function confirm_payment($id_order, $id_user, $moderation)
    {
        $orders = new Model_Order();

        $orders->where('id_order','=',$id_order)
                         ->where('status','=', 0)
                         ->limit(1)->find();

        $id_ad = $orders->id_ad;
        $id_user = $orders->id_user;

        $product_find = new Model_Ad();
        $product_find = $product_find->where('id_ad', '=', $id_ad)->limit(1)->find();
        
        // update orders
        if($orders->loaded())
        {
            $orders->status = 1;
            $orders->pay_date = Date::unix2mysql(time());
            try {
                $orders->save();
            } catch (Exception $e) {
                echo $e;  
            }
        }

        // update product 
        if(is_numeric($orders->id_product))
        {

            if($moderation == 2)
            {
                $product_find->published = Date::unix2mysql(time());
                $product_find->status = 1;
                try {
                    $product_find->save();
                } catch (Exception $e) {
                    echo $e;
                }
            }
            else if($moderation == 3)
            {
                $product_find->published = Date::unix2mysql(time());
                
                try 
                {
                    $product_find->save();      
                } catch (Exception $e) {
                   
                }   
            }
        }
        elseif($orders->id_product == core::config('general.ID-pay_to_go_on_top'))
        {
            $product_find->published = Date::unix2mysql(time());
            try {
                $product_find->save();
            } catch (Exception $e) {
                echo $e;
            }
        }
        elseif ($orders->id_product == core::config('general.ID-pay_to_go_on_feature'))
        {
            $product_find->featured = Date::unix2mysql(time() + (core::config('general.featured_timer') * 24 * 60 * 60));
            try {
                $product_find->save();
            } catch (Exception $e) {
                echo $e;
            }
        }
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

    public function _gen_img_path($seotitle, $created)
    {
        $obj_date = date_parse($created); // convert date to array 
        
            $year = substr($obj_date['year'], -2); // take last 2 integers 
        
        // check for length, because original path is with 2 integers 
        if(strlen($obj_date['month']) != 2)
            $month = '0'.$obj_date['month'];
        else
            $month = $obj_date['month'];
        
        if(strlen($obj_date['day']) != 2)
            $day = '0'.$obj_date['day'];
        else
            $day = $obj_date['day'];

        $directory = 'upload/'.$year.'/'.$month.'/'.$day.'/'.$seotitle.'/';
        
        return $directory;
    }

    public function save_file($file, $seotitle, $created)
    {
        if ( 
        ! Upload::valid($file) OR
        ! Upload::not_empty($file) OR
        ! Upload::type($file, array('doc', 'docx', 'pdf')) OR
        ! Upload::size($file, array('1M')))
        {
            $file = NULL;
        }

        if($file !== NULL)
        {
            if(!is_dir($path = $this->_gen_img_path($seotitle, Date::format(time(), 'y/m/d'))))
            {
                $directory = DOCROOT.$path;
                d($directory);

                if ($file_upload = Upload::save($file, NULL, $directory))
                {
                    $name = strtolower(Text::random('alnum',20));
                    $filename_original = $name.'_file';
                    Upload::save($directory.$filename_original);
                    
                    // Delete the temporary file
                    unlink($file_upload);
                }
                else
                { 
                    echo 'bla';
                    return FALSE;

                }
            } echo 'asd';
        }
    }

} // END Model_ad
