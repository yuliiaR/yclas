<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User model
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_User extends Model_OC_User {

    /**
     * saves the user review rates recalculating it
     * @return [type] [description]
     */
    public function recalculate_rate()
    {
        if($this->loaded())
        {
            //get all the rates and divide by them
            $this->rate = Model_Review::get_user_rate($this);
            $this->save();
            return $this->rate;
        }
        return FALSE;
    }

    /**
     * Deletes a single record while ignoring relationships.
     *
     * @chainable
     * @throws Kohana_Exception
     * @return ORM
     */
    public function delete()
    {
        if ( ! $this->_loaded)
            throw new Kohana_Exception('Cannot delete :model model because it is not loaded.', array(':model' => $this->_object_name));

        //remove image
        $this->delete_image();

        //remove ads, will remove reviews, images etc...
        $ads = new Model_Ad();
        $ads = $ads->where('id_user','=',$this->id_user)->find_all();
       
        foreach ($ads as $ad) 
            $ad->delete();
        
        //delete favorites
        DB::delete('favorites')->where('id_user', '=',$this->id_user)->execute();
        
        //delete reviews
        DB::delete('reviews')->where('id_user', '=',$this->id_user)->execute();

        //delete orders
        DB::delete('orders')->where('id_user', '=',$this->id_user)->execute();

        //remove visits ads
        DB::update('visits')->set(array('id_user' => NULL))->where('id_user', '=',$this->id_user)->execute();

        //delete subscribtions
        DB::delete('subscribers')->where('id_user', '=',$this->id_user)->execute();

        //delete posts
        DB::delete('posts')->where('id_user', '=',$this->id_user)->execute();

        parent::delete();
    }
    
    /**
     * get user ad contacts
     * @return array [description]
     */
    public function contacts()
    {
        if($this->loaded())
        {
            //get cookie contact_notification
            $theme = (isset($_COOKIE['contact_notification']))? $_COOKIE['contact_notification']:0;
            
            $query = DB::select('a.id_ad')
                        ->select('a.title')
                        ->select('a.seotitle')
                        ->select('v.id_visit')
                        ->select('v.created')
                        ->from(array('ads', 'a'))
                        ->join(array('visits', 'v'),'INNER')
                        ->on('a.id_ad','=','v.id_ad')
                        ->where('a.id_user','=',$this->id_user)
                        ->where('v.contacted','=','1')
                        ->where('v.created','>',Date::unix2mysql($theme))
                        ->order_by('v.created', 'DESC');
            
            if (!isset($_COOKIE['contact_notification']))
                $query->limit(5);
            
            return $query->execute();
        }
        return FALSE;
    }
    
    /**
     * checks if we have stored user's lat/lng
     * @return array/boolean
     */
    public static function get_userlatlng()
    {
        if (isset($_COOKIE['mylat'])
            AND is_numeric($_COOKIE['mylat'])
            AND isset($_COOKIE['mylng'])
            AND is_numeric($_COOKIE['mylng']))
        {
            return array(   "lat" => $_COOKIE['mylat'],
                            "lng" => $_COOKIE['mylng'],
                        );
        }
        else return FALSE;
    }


} // END Model_User