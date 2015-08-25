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
     * @param  Model_User $user_from 
     * @param  Model_User $user_to 
     * @param  integer $id_ad        
     * @param  integer $id_message_parent 
     * @param  integer $price        negotiate price optionsl
     * @return bool / model_message              
     */
    private static function send($message_text, $user_from, $user_to, $id_ad = NULL, $id_message_parent = NULL, $price = NULL)
    {
        //cant be the same...
        if ($id_user_to!==$id_user_from)
        {
            $message = new Model_Message();

            $message->message      = $message_text;
            $message->id_user_from = $user_from->id_user;
            $message->id_user_to   = $user_to->id_user;

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

                //didnt have a parent so we set the parent for the same
                if (!is_numeric($id_message_parent))
                {
                    $message->id_message_parent = $message->id_message;
                    $message->save();
                }
                
                //notify user
                $data = array('id_message' => $message->id_message_parent,
                              'title'      => sprintf(__('New Message from %s'),$message->from->name));
                $message->to->push_notification($message_text,$data);

                return $message;

            } catch (Exception $e) {
                return FALSE;
            }
            
        }

        return FALSE;
    }

    /**
     * send message to a user
     * @param  string $message      
     * @param  Model_User $user_from 
     * @param  Model_User $user_to        
     * @return bool / model_message              
     */
    public static function send_user($message, $user_from, $user_to)
    {
        //check if we already have a thread for that user...then its a reply not a new message.
        $msg_thread = new Model_Message();

        $msg_thread ->where('id_message','=',DB::expr('id_message_parent'))
                    ->where('id_ad','is',NULL)
                    ->where('id_user_to','=',$user_to->id_user)
                    ->where('status','!=',Model_Message::STATUS_SPAM)
                    ->limit(1)->find();

        //actually reply not new thread....
        if ($msg_thread->loaded())
            return self::reply($message, $user_from, $msg_thread->id_message);
        else
        {
            $ret = self::send($message, $user_from, $user_to);
            //send email only if no device ID since he got the push notification already
            if ($ret !== FALSE AND !isset($this->device_id))
            {
                $user->email('messaging-user-contact', array(   '[FROM.NAME]'   => $user_from->name,
                                                                '[TO.NAME]'     => $user_to->name,
                                                                '[DESCRIPTION]' => $message,
                                                                '[URL.QL]'      => $user_to->ql('oc-panel', array( 'controller'    => 'messages',
                                                                                                                'action'        => 'message',
                                                                                                                'id'            => $ret->id_message)))
                            );
            }
            return $ret;
        }    
    }

    /**
     * send message to an advertisement
     * @param  string $message      
     * @param  Model_User $user_from
     * @param  integer $id_ad        
     * @param  integer $price        negotiate price optionsl
     * @return bool / model_message              
     */
    public static function send_ad($message, $user_from,$id_ad, $price = NULL)
    {
        //get the ad if its available, and user to who we need to contact
        $ad = new Model_Ad();
        $ad->where('id_ad','=',$id_ad)
            ->where('status','=',Model_Ad::STATUS_PUBLISHED)->find();
        //ad loaded and is not your ad....
        if ($ad->loaded() == TRUE AND $user_from->id_user!=$ad->id_user)
        {
            //check if we already have a thread for that ad and user...then its a reply not a new message.
            $msg_thread = new Model_Message();

            $msg_thread ->where('id_message','=',DB::expr('id_message_parent'))
                        ->where('id_ad','=',$id_ad)
                        ->where('id_user_from', '=',$user_from->id_user)
                        ->where('status','!=',Model_Message::STATUS_SPAM)->limit(1)->find();

            //actually reply not new thread....
            if ($msg_thread->loaded())
                return self::reply($message, $user_from, $msg_thread->id_message, $price);
            else
            {
                $ret = self::send($message, $user_from, $ad,$id_ad,NULL,$price);

                //send email only if no device ID since he got the push notification already
                if ($ret !== FALSE AND !isset($this->device_id))
                {
                    $ad->user->email('messaging-ad-contact', array( '[AD.NAME]'     => $ad->title,
                                                                    '[FROM.NAME]'   => $user_from->name,
                                                                    '[TO.NAME]'     => $ad->user->name,
                                                                    '[DESCRIPTION]' => $message,
                                                                    '[URL.QL]'      => $ad->user->ql('oc-panel', array( 'controller'    => 'messages',
                                                                                                                        'action'        => 'message',
                                                                                                                        'id'            => $ret->id_message)))
                                        );
                }
                return $ret;
            }
        
        }
        return FALSE;
        
    }

    /**
     * replies to a thread
     * @param  string $message           
     * @param  Model_User $user_from      
     * @param  integer $id_message_parent 
     * @param  integer $price , optional , negotiation of price           
     * @return bool    / model_message                  
     */
    public static function reply($message, $user_from, $id_message_parent, $price = NULL)
    {
        $msg_thread = new Model_Message();

        $msg_thread->where('id_message','=',$id_message_parent)
                    ->where('id_message_parent','=',$id_message_parent)
                    ->where_open()
                    ->where('id_user_from', '=',$user_from->id_user)
                    ->or_where('id_user_to','=',$user_from->id_user)
                    ->where_close()
                    ->where('status','!=',Model_Message::STATUS_SPAM)
                    ->find();

        if ($msg_thread->loaded())
        {
            //to who? if from is the same then send to TO, else to from
            $user_to = ($msg_thread->id_user_from == $user_from->id_user)? $msg_thread->id_user_to:$msg_thread->id_user_from;
            $user_to = new Model_User($user_to);

            $ret = self::send($message, $user_from, $user_to, $msg_thread->id_ad, $id_message_parent, $price);

            //send email only if no device ID since he got the push notification already
            if ($ret !== FALSE AND !isset($this->device_id))
            {                
                //email title
                if ($msg_thread->id_ad !== NULL)
                    $email_title = $msg_thread->ad->title;
                else
                    $email_title = sprintf(__('Direct message from %s'), $user_from->name);
                
                $user_to->email('messaging-reply', array(   '[TITLE]'       => $email_title,
                                                            '[DESCRIPTION]' => core::post('message'),
                                                            '[AD.NAME]'     => isset($msg_thread->ad->title) ? $msg_thread->ad->title : NULL,
                                                            '[FROM.NAME]'   => $user_from->name,
                                                            '[TO.NAME]'     => $user_to->name,
                                                            '[URL.QL]'      => $user_to->ql('oc-panel', array(  'controller'    => 'messages',
                                                                                                                'action'        => 'message',
                                                                                                                'id'            => $this->request->param('id'))))
                                );
            }

            return $ret;
        }

        return FALSE;
    }

    /**
     * returns all the messages from a parent
     * @param  integer $id_message_thread 
     * @param  Model_User $user           
     * @return bool / array                    
     */
    public static function get_thread($id_message_thread,$user)
    {
        $msg_thread = new Model_Message();

        $msg_thread->where('id_message','=',$id_message_thread)
                    ->where('id_message_parent','=',$id_message_thread)
                    ->where_open()
                    ->where('id_user_from','=', $user->id_user)
                    ->or_where('id_user_to','=',$user->id_user)
                    ->where_close()
                    ->where('status','!=',Model_Message::STATUS_SPAM)
                    ->find();

        if ($msg_thread->loaded())
        {
            //get all the messages where parent = $is_msg order by created asc
            $messages = new Model_Message();
            $messages = $messages->where('id_message_parent','=',$id_message_thread)
                                ->where('status','!=',Model_Message::STATUS_SPAM)
                                ->order_by('created','asc')->find_all();
            
            foreach ($messages as $message)
                $m[$message->id_message] = $message->mark_read($user);

            return $m;
        }

        return FALSE;
    }

    /**
     * returns all the threads for a user
     * @param  Model_User $user 
     * @return Model_Message                    
     */
    public static function get_threads($user)
    {

        //get the model ;)
        $messages = new Model_Message();

        //I get first the last message grouped by parent.
        
        /*SELECT m1.id_message FROM oc2_messages m1 
        LEFT JOIN oc2_messages m2 
        ON ( m1.id_message<m2.id_message and m1.id_message_parent=m2.id_message_parent )
        WHERE m2.id_message IS NULL AND (m1.id_user_from = 1 OR m1.id_user_to = 1)*/

        //I get first the last message grouped by parent.
        //we do this since I need to know if was written, the text and the creation date
        $query = DB::select('m1.id_message')
                ->from(array('messages','m1'))
                    ->join(array('messages','m2'),'LEFT')
                        ->on('m1.id_message','<','m2.id_message')
                        ->on('m1.id_message_parent','=','m2.id_message_parent')
                ->where('m2.id_message','IS',NULL)
                ->where_open()
                ->where('m1.id_user_from','=', $user->id_user)
                ->or_where('m1.id_user_to','=',$user->id_user)
                ->where_close()
                ->execute();

        $ids = $query->as_array();
        
        //filter only if theres results
        if (count($ids)>0)
            $messages->where('id_message','IN',$ids);
        else
            $messages->where('id_message','=',0);
                 
        return $messages;
    }

    /**
     * returns all the unread threads for a user
     * @param  Model_User $user        
     * @return Model_Message                    
     */
    public static function get_unread_threads($user)
    {      
        //get the model ;)
        $messages = new Model_Message();


        //I get first the last message grouped by parent.
        //we do this since I need to know if was written, the text and the creation date
        $query = DB::select('m1.id_message')
                ->from(array('messages','m1'))
                    ->join(array('messages','m2'),'LEFT')
                        ->on('m1.id_message','<','m2.id_message')
                        ->on('m1.id_message_parent','=','m2.id_message_parent')
                ->where('m2.id_message','IS',NULL)
                ->where('m1.id_user_to','=',$user->id_user)
                ->where('m1.status','=',Model_Message::STATUS_NOTREAD)
                ->execute();

        $ids = $query->as_array();

        //filter only if theres results
        if (count($ids)>0)
            $messages->where('id_message','IN',$ids);
        else
            $messages->where('id_message','=',0);
                 
        return $messages;
    }


    /**
     * mark message as read if user is the receiver and not read
     * @param  Model_User $user
     * @return Model_Message
     */
    public function mark_read($user)
    {
        if (!$this->loaded())
            return FALSE;

        if ($this->id_user_to == $user->id_user AND $this->status == Model_Message::STATUS_NOTREAD)
        {
            $this->read_date = Date::unix2mysql();
            $this->status    = Model_Message::STATUS_READ;
            try {
                $this->save();
            } catch (Exception $e) {}
            
        }

        return $this;
    }

}