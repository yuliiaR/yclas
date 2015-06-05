<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product reviews
 *
 * @author      Chema <chema@open-classifieds.com>
 * @author      Xavi <xavi@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Message extends ORM {

    /**
     * status constants
     */
    const STATUS_NOTREAD = 0; 
    const STATUS_READ   = 1; 
    const STATUS_SPAM   = 5;

    
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'messages';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_message';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'from' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user_from',
            ),
        'to' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user_to',
            ),
        'parent'   => array(
                'model'       => 'message', 
                'foreign_key' => 'id_message_parent',
            ),
        'ad'   => array(
                'model'       => 'ad', 
                'foreign_key' => 'id_ad',
            ),
    );

    /**
     * sends a message
     * @param  string $message_text      
     * @param  integer $id_user_from 
     * @param  integer $id_user_to 
     * @param  integer $id_ad        
     * @param  integer $id_message_parent 
     * @param  integer $price        negotiate price optionsl
     * @return bool / model_message              
     */
    private static function send($message_text, $id_user_from, $id_user_to, $id_ad = NULL, $id_message_parent = NULL, $price = NULL)
    {
        //cant be the same...
        if ($id_user_to!==$id_user_from)
        {
            $message = new Model_Message();

            $message->message      = $message_text;
            $message->id_user_from = $id_user_from;
            $message->id_user_to   = $id_user_to;

            //message to an ad. we have verified before the ad, and pass the correct user
            if (is_numeric($id_ad))
                $message->id_ad = $id_ad;

            //set current message the correct thread, 
            //we trust this since comes fom a function where we validate tihs user can post in that thread
            if (is_numeric($id_message_parent))
                $message->id_message_parent = $id_message_parent;
            

            //has some price?
            if (is_numeric($price))
                $message->price = $price;

            try {
                $message->save();

                return $message;
                //send email?
            } catch (Exception $e) {
                return FALSE;
            }
            
        }

        return FALSE;
    }

    /**
     * send message to a user
     * @param  string $message      
     * @param  integer $id_user_from 
     * @param  integer $id_user_to        
     * @return bool / model_message              
     */
    public static function send_user($message, $id_user_from, $id_user_to)
    {
        return self::send($message, $id_user_from, $id_user_to);
    }

    /**
     * send message to an advertisement
     * @param  string $message      
     * @param  integer $id_user_from 
     * @param  integer $id_ad        
     * @param  integer $price        negotiate price optionsl
     * @return bool / model_message              
     */
    public static function send_ad($message, $id_user_from,$id_ad, $price = NULL)
    {
        //get the ad if its available, and user to who we need to contact
        $ad = new Model_Ad();
        $ad->where('id_ad','=',$id_ad)
            ->where('status','=',Model_Ad::STATUS_PUBLISHED)->find();
        //ad loaded and is not your ad....
        if ($ad->loaded() == TRUE AND $id_user_from!=$ad->id_user)
            return self::send($message, $id_user_from, $ad->id_user,$id_ad,NULL,$price);
        
        return FALSE;
        
    }

    /**
     * replies to a thread
     * @param  string $message           
     * @param  integer $id_user_from      
     * @param  integer $id_message_parent 
     * @param  integer $price , optional , negotiation of price           
     * @return bool    / model_message                  
     */
    public static function reply($message, $id_user_from, $id_message_parent, $price = NULL)
    {
        $msg_thread = new Model_Message();

        $msg_thread->where('id_message','=',$id_message_parent)
                    ->where('id_message_parent','IS',NULL)
                    ->where_open()
                    ->where('id_user_from', '=',$id_user_from)
                    ->or_where('id_user_to','=',$id_user_from)
                    ->where_close()
                    ->where('status','!=',Model_Message::STATUS_SPAM)
                    ->find();

        if ($msg_thread->loaded())
        {
            //to who? if from is the same then send to TO, else to from
            $id_user_to = ($msg_thread->id_user_from == $id_user_from)? $msg_thread->id_user_to:$msg_thread->id_user_from;

            //message was sent succesfully
            if (($msg = self::send($message, $id_user_from, $id_user_to, $msg_thread->id_ad, $id_message_parent, $price))!==FALSE)
            {
                //we update the parent so later we can sort the threads by last updated
                $msg_thread->updated = Date::unix2mysql();

                //in case theres a new message in the thread we mark it as unread, but only if its not the creator of the thread
                if ($msg_thread->id_user_from != $id_user_to)
                    $msg_thread->status  = Model_Message::STATUS_NOTREAD;

                try {
                    $msg_thread->save();
                    return $msg;
                } catch (Exception $e) {
                    return FALSE;
                }
            }

        }

        return FALSE;
    }

    /**
     * returns all the messages from a parent
     * @param  integer $id_message_thread 
     * @param  integer $id_user           
     * @return bool / array                    
     */
    public static function get_thread($id_message_thread,$id_user)
    {
        $msg_thread = new Model_Message();

        $msg_thread->where('id_message','=',$id_message_thread)
                    ->where('id_message_parent','IS',NULL)
                    ->where_open()
                    ->where('id_user_from','=',$id_user)
                    ->or_where('id_user_to','=',$id_user)
                    ->where_close()
                    ->where('status','!=',Model_Message::STATUS_SPAM)
                    ->find();

        if ($msg_thread->loaded())
        {
            //mark initial thread as read if wasnt
            $msg_thread = $msg_thread->mark_read($id_user);

            //addig the first message
            $m[$id_message_thread] = $msg_thread;

            //get all the messages where parent = $is_msg order by created asc
            $messages = new Model_Message();
            $messages = $messages->where('id_message_parent','=',$id_message_thread)
                                ->where('status','!=',Model_Message::STATUS_SPAM)
                                ->order_by('created','asc')->find_all();
            
            foreach ($messages as $message)
                $m[$message->id_message] = $message->mark_read($id_user);

            return $m;
        }

        return FALSE;
    }

    /**
     * returns all the threads for a user
     * @param  integer $id_user           
     * @return Model_Message                    
     */
    public static function get_threads($id_user)
    {
        $messages = new Model_Message();

        $messages->where('id_message_parent','IS',NULL)
                 ->where_open()
                 ->where('id_user_from','=',$id_user)
                 ->or_where('id_user_to','=',$id_user)
                 ->where_close();

        return $messages;

    }



    /**
     * mark message as read if user is the receiver and not read
     * @param  [type] $id_user [description]
     * @return [type]          [description]
     */
    public function mark_read($id_user)
    {
        if (!$this->loaded())
            return FALSE;

        if ($this->id_user_to == $id_user AND $this->status == Model_Message::STATUS_NOTREAD)
        {
            $this->date_read = Date::unix2mysql();
            $this->status = Model_Message::STATUS_READ;
            try {
                $this->save();
            } catch (Exception $e) {}
            
        }

        return $this;
    }

}