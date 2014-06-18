<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Slobodan <slobodan@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Order extends ORM {


	/**
	 * Table name to use
	 *
	 * @access	protected
	 * @var		string	$_table_name default [singular model name]
	 */
	protected $_table_name = 'orders';

	/**
	 * Column to use as primary key
	 *
	 * @access	protected
	 * @var		string	$_primary_key default [id]
	 */
	protected $_primary_key = 'id_order';

	/**
	 * Status constants
	 */
    const STATUS_CREATED        = 0;   // just created
    const STATUS_PAID           = 1;   // paid!
    const STATUS_REFUSED        = 5;   //tried to paid but not succeed
    const STATUS_REFUND         = 99;  //we refunded the money

    /**
     * @var  array  Available statuses array
     */
    public static $statuses = array(
        self::STATUS_CREATED      =>  'Created',
        self::STATUS_PAID         =>  'Paid',
        self::STATUS_REFUSED      =>  'Refused',
        self::STATUS_REFUND       =>  'Refund',
    );

    /**
     * Id of products 
     */
    const CATEGORY_PRODUCT      = 1; //paid to post in a paid category
    const TO_TOP                = 2; //paid to return the ad to the first page
    const TO_FEATURED           = 3; // paid to featured an ad in the site
    const AD_SELL               = 4; // a customer paid to buy the item/ad 

    /**
     * @var  array  Available statuses array
     */
    public static $products = array(
        self::CATEGORY_PRODUCT  =>  'Paid category',
        self::TO_TOP            =>  'Top up ad',
        self::TO_FEATURED       =>  'Feature ad',
        self::AD_SELL           =>  'Advertisement sold',
    );

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

    /**
     * confirm payment for order
     *
     * @param string    $id_order [unique indentifier of order]
     */
    public function confirm_payment()
    { 
        $moderation = core::config('general.moderation');

        // update orders
        if($this->loaded())
        {
            $advert = new Model_Ad($this->id_ad);
            $user = new Model_User($this->id_user);

            $this->status = self::STATUS_PAID;
            $this->pay_date = Date::unix2mysql(time());

            try {
                $this->save();

            } catch (Exception $e) {
                echo $e;  
            }
        
            // update product
            if($this->id_product == Model_Order::AD_SELL)
            {
                // decrease limit of ads, if 0 deactivate
                if($advert->stock >0)
                {
                    $stock = $advert->stock-1;

                    if($stock == 0)
                    {
                        $advert->status = Model_Ad::STATUS_UNAVAILABLE;
                        
                        //we get the QL, and force the regen of token for security
                        $url_edit = $user->ql('oc-panel',array( 'controller'=> 'profile', 
                                                                'action'    => 'update',
                                                                'id'        => $advert->id_ad),TRUE);

                        $email_content = array( '[URL.EDIT]'        =>$url_edit,
                                                '[AD.TITLE]'        =>$advert->title);

                        // send email to ad OWNER
                        $user_owner = new Model_User($this->ad->id_user);
                        $ret = $user_owner->email('out_of_stock',$email_content);

                    }
                
                    $advert->stock = $stock;
                }

                try {
                    $advert->save();


                    //we get the QL, and force the regen of token for security
                    $url_ad = $user->ql('ad', array('category'=>$advert->id_category,
                                                    'seotitle'=>$advert->seotitle), TRUE);

                    $email_content = array('[URL.AD]'      =>$url_ad,
                                            '[AD.TITLE]'     =>$advert->title,
                                            '[ORDER.ID]'      =>$this->id_order,
                                            '[PRODUCT.ID]'    =>$this->id_product);
                    // send email to BUYER
                    $ret = $user->email('ads_purchased',$email_content);
                    // send email to ad OWNER
                    $user_owner = new Model_User($this->ad->id_user);
                    $ret = $user_owner->email('ads_sold',$email_content);

                } catch (Exception $e) {
                    echo $e;
                }
                
            } 
            elseif($this->id_product == Model_Order::CATEGORY_PRODUCT)
            {

                if($moderation == Model_Ad::PAYMENT_ON)
                {
                    $advert->published = Date::unix2mysql(time());
                    $advert->status = Model_Ad::STATUS_PUBLISHED;

                    try {
                        $advert->save();

                        //we get the QL, and force the regen of token for security
                        $url_cont = $user->ql('contact', array(),TRUE);
                        $url_ad = $user->ql('ad', array('category'=>$advert->id_category,
                                                        'seotitle'=>$advert->seotitle), TRUE);

                        $ret = $user->email('ads_user_check',array('[URL.CONTACT]'  =>$url_cont,
                                                                    '[URL.AD]'      =>$url_ad,
                                                                    '[AD.NAME]'     =>$advert->title));

                    } catch (Exception $e) {
                        echo $e;
                    }
                }
                else if($moderation == Model_Ad::PAYMENT_MODERATION)
                {
                    $advert->published = Date::unix2mysql(time());
                    
                    try 
                    {
                        $advert->save(); 

                        $edit_url   = Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$advert->id_ad));
                        $delete_url = Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$advert->id_ad));

                        //we get the QL, and force the regen of token for security
                        $url_ql = $user->ql('oc-panel',array( 'controller'=> 'profile', 
                                                              'action'    => 'update',
                                                              'id'        => $this->id_ad),TRUE);

                        $ret = $user->email('ads_notify',array('[URL.QL]'=>$url_ql,
                                                               '[AD.NAME]'=>$advert->title,
                                                               '[URL.EDITAD]'=>$edit_url,
                                                               '[URL.DELETEAD]'=>$delete_url));     
                    } catch (Exception $e) {
                       
                    }   
                }
            }
            elseif($this->id_product == Model_Order::TO_TOP)
            {
                $advert->published = Date::unix2mysql(time());
                $advert->status = Model_Ad::STATUS_PUBLISHED;
                try {
                    $advert->save();

                } catch (Exception $e) {
                    echo $e;
                }
            }
            elseif ($this->id_product == Model_Order::TO_FEATURED)
            {
                $advert->featured = Date::unix2mysql(time() + (core::config('payment.featured_days') * 24 * 60 * 60));
                $advert->status = Model_Ad::STATUS_PUBLISHED;
                try {
                    $advert->save();
                } catch (Exception $e) {
                    echo $e;
                }
            }
        }
    }

    /**
	 * [set_new_order] Creates new order with given parameters, and gets newlly created id_order
	 * @param  [array] $ord_data array of necessary parameters to create order
	 * @return [int] self order id
	 */
	public static function set_new_order($ord_data)
	{

		//create order		
		$order = new self;

		$order->id_user       = $ord_data['id_user'];
		$order->id_ad         = $ord_data['id_ad'];
		$order->id_product    = $ord_data['id_product'];
		$order->paymethod     = $ord_data['paymethod'];
		$order->currency      = $ord_data['currency'];
		$order->amount        = $ord_data['amount'];
        $order->description   = $ord_data['description'];

		try 
		{
			$order->save();
		} 
		catch (Exception $e){
			Kohana::$log->add(Log::ERROR, Kohana_Exception::text($e));
		} 

		return $order->id_order;
	}

    /**
     * [make_new_order] Process data related to new advert and makes call to payment system. 
     * Controlls price of a product and calls function for seting new order to create new order in DB 
     * @param  [array] $data        [Array with data related to advert]
     * @param  [int] $usr           [user id]
     * @param  [string] $seotitle   [seotitle of advertisement]
     * @return [view]               [order_id or null if no proce set]
     */
    public function make_new_order($data, $usr, $seotitle)
    {
        $category   = new Model_Category();
        $cat        = $category->where('id_category', '=', $data['cat'])->limit(1)->find();

        // check category price, if 0 check parent
        if($cat->price == 0)
        {
            $parent     = $cat->id_category_parent;
            $cat_parent = new Model_Category();
            $cat_parent = $cat_parent->where('id_category', '=', $parent)->limit(1)->find();

            if($cat_parent->price == 0) // @TODO add case of moderation + payment (moderation = 5)
                return NULL;
            else
                $amount = $cat_parent->price;
        }
        else
            $amount = $cat->price;
        
        
        // make order 
        $payer_id = $usr; 
        $id_product = Model_Order::CATEGORY_PRODUCT;

        $ad = new Model_Ad();
        $ad = $ad->where('seotitle', '=', $seotitle)->limit(1)->find();

        $ord_data = array('id_user'     => $payer_id,
                          'id_ad'       => $ad->id_ad,
                          'id_product'  => $id_product,
                          'paymethod'   => 'paypal', // @TODO - to strict
                          'currency'    => core::config('payment.paypal_currency'),
                          'amount'      => $amount,
                          'description' => $cat->seoname);

        $order_id = new self; // create order , and returns order id
        $order_id = $this->set_new_order($ord_data);

        return $order_id;
    }

    public function exclude_fields()
    {
        return array('created','parent_deep','order');
    }
    
    protected $_table_columns =  
array (
  'id_order' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_order',
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
  'id_ad' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_ad',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 3,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_product' => 
  array (
    'type' => 'string',
    'column_name' => 'id_product',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'character_maximum_length' => '20',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'paymethod' => 
  array (
    'type' => 'string',
    'column_name' => 'paymethod',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 5,
    'character_maximum_length' => '20',
    'collation_name' => 'utf8_general_ci',
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
    'ordinal_position' => 6,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'pay_date' => 
  array (
    'type' => 'string',
    'column_name' => 'pay_date',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 7,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'currency' => 
  array (
    'type' => 'string',
    'exact' => true,
    'column_name' => 'currency',
    'column_default' => NULL,
    'data_type' => 'char',
    'is_nullable' => false,
    'ordinal_position' => 8,
    'character_maximum_length' => '3',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'amount' => 
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'amount',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'numeric_scale' => '3',
    'numeric_precision' => '14',
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
    'ordinal_position' => 10,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'description' => 
  array (
    'type' => 'string',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 11,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);
}
