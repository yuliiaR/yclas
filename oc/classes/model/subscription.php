<?php defined('SYSPATH') or die('No direct script access.');
/**
 * plan for memberships
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Subscription extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'subscriptions';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_subscription';

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
                    'amount_ads_left'   => array(array('numeric')),
                    'amount_ads'        => array(array('numeric')),
			    );
    }

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
        'plan' => array(
                'model'       => 'plan',
                'foreign_key' => 'id_plan',
            ),
        'order' => array(
                'model'       => 'order',
                'foreign_key' => 'id_order',
            ),
    );

    /**
     * new order therefore new subscription created
     * @param  Model_Order $order 
     * @return void             
     */
    public static function new_order(Model_Order $order)
    {
        $plan = new Model_Plan($order->id_product);

        //disable all the previous membership
        DB::update('subscriptions')->set(array('status' => 0))->where('id_user', '=',$order->id_user)->execute();
        
        //create a new subscription for this product
        $subscription = new Model_Subscription();
        $subscription->id_order = $order->id_order;
        $subscription->id_user  = $order->id_user;
        $subscription->id_plan  = $plan->id_plan;
        $subscription->amount_ads       = $plan->amount_ads;
        $subscription->amount_ads_left  = $plan->amount_ads;
        $subscription->expire_date      = Date::unix2mysql(strtotime('+'.$plan->days.' days'));
        $subscription->status   = 1;
        
        try {
            $subscription->save();
        } catch (Exception $e) {
            throw HTTP_Exception::factory(500,$e->getMessage());  
        }
    }

    /**
     * when there a new ad we decrease the subscription
     * @param  Model_User $user user to decrease ad
     * @return void           
     */
    public static function new_ad(Model_User $user)
    {
        if (Core::config('general.subscriptions')==TRUE)
        {
            $subscription = $user->subscription();
            if ($subscription->loaded())
            {
                $subscription->amount_ads_left--;
                try {
                    $subscription->save();
                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());  
                }
            }
        }
    }

/// protected $_table_columns =  TODO!!


} // END Model_Plan