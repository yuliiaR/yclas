<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product reviews
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Review extends ORM {

    /**
     * status constants
     */
    const STATUS_NOACTIVE = 0; 
    const STATUS_ACTIVE   = 1; 

    const RATE_MAX   = 5; 
    
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'reviews';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_review';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'ad' => array(
                'model'       => 'ad',
                'foreign_key' => 'id_ad',
            ),
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
    );



    public function exclude_fields()
    {
        return array('created');
    }

    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   

        $form->fields['id_ad']['display_as']      = 'text'; 
        $form->fields['id_user']['display_as']    = 'text';
        $form->fields['description']['display_as']    = 'textarea';
    }

    /**
     * returns the ad rate from all the reviews
     * @param  Model_Ad $ad [description]
     * @return [type]                 [description]
     */
    public static function get_ad_rate(Model_Ad $ad)
    {
        $query = DB::select(DB::expr('AVG(rate) rate'))
                        ->from('reviews')
                        ->where('id_ad','=',$ad->id_ad)
                        ->where('status','=',Model_Review::STATUS_ACTIVE)
                        ->group_by('id_ad')
                        ->execute();

        $rates = $query->as_array();

        return (isset($rates[0]))?round($rates[0]['rate'],2):FALSE;

    }

    /**
     * returns the user rate from all the reviews
     * @param  Model_User $user [description]
     * @return [type]                 [description]
     */
    public static function get_user_rate(Model_User $user)
    {
        $db_prefix  = Database::instance('default')->table_prefix();

        $query = DB::select(DB::expr('AVG('.$db_prefix.'reviews.rate) rates'))
                            ->from('reviews')
                            ->join('ads','RIGHT')
                        ->using('id_ad')
                            ->where('ads.id_user','=',$user->id_user)
                            ->where('reviews.status','=',Model_Review::STATUS_ACTIVE)
                        ->group_by('reviews.id_ad')
                        ->execute();

        $rates = $query->as_array();

        return (isset($rates[0]))?round($rates[0]['rates'],2):FALSE;
    }

}