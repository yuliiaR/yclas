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

} // END Model_ad
