<?php defined('SYSPATH') or die('No direct script access.');

/**
* paypal class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>, Slobodan Josifovic <slobodan@open-classifieds.com>
* @license GPL v3
*/

class Controller_Payment_Paypal extends Controller{
	

	public function after()
	{

	}
	
	public function action_ipn()
	{
        //todo delete
        //paypal::validate_ipn();

		$this->auto_render = FALSE;

		//START PAYPAL IPN
		//manual checks
		$id_order         = Core::post('item_number');
		$paypal_amount    = Core::post('mc_gross');
		$payer_id         = Core::post('payer_id');

		//retrieve info for the item in DB
		$order = new Model_Order();
		$order = $order->where('id_order', '=', $id_order)
					   ->where('status', '=', Model_Order::STATUS_CREATED)
					   ->limit(1)->find();
		
		if($order->loaded())
		{
			// detect product to be processed 
			if ($order->id_product != Paypal::category_product)
			{
				$id_category = new Model_Category();
				$id_category = $id_category->where('id_category', '=', $order->id_product)->limit(1)->find();
				$product_id  = $id_category->id_category;
			}
			else
			{
				$product_id = $order->id_product;
			} 

            //order is from a payment done to the owner of the ad
            if ($order->id_product == Paypal::advertisement_sell)
            {
                $user_paid = $order->ad->user;

                $receiver_correct = (Core::post('receiver_email') == $user_paid->email  OR Core::post('business')  == $user_paid->emai);
            }
            //any other payment goes to classifieds site payment
            else
            { 
                $receiver_correct = (Core::post('receiver_email') == core::config('payment.paypal_account')  OR Core::post('business')  == core::config('payment.paypal_account'));
            }

            //same amount and same currency
			if (Core::post('mc_gross')  == number_format($order->amount, 2, '.', '')
				AND  Core::post('mc_currency') == core::config('payment.paypal_currency') AND  $receiver_correct)
			{
                //same price , currency and email no cheating ;)
				if (paypal::validate_ipn()) 
				{
					$order->confirm_payment();	
				} //payment succeed and we confirm the post ;) (CALL TO LOGIC PUT IN ctrl AD)

				else
				{
					Kohana::$log->add(Log::ERROR, 'A payment has been made but is flagged as INVALID');
					$this->response->body('KO');
				}	
			} 
			else //trying to cheat....
			{
				Kohana::$log->add(Log::ERROR, 'Attempt illegal actions with transaction');
				$this->response->body('KO');
			}
		}// END order loaded
		else
		{
            Kohana::$log->add(Log::ERROR, 'Order not loaded');
            $this->response->body('KO');
		}

		$this->response->body('OK');
	} 

	/**
	 * [action_form] generates the form to pay at paypal
	 */
	public function action_form()
	{ 
		$this->auto_render = FALSE;

		$order_id = $this->request->param('id');


		$order = new Model_Order();

        $order->where('id_order','=',$order_id)
            ->where('status','=',Model_Order::STATUS_CREATED)
            ->limit(1)->find();

        if ($order->loaded())
        {
        	// dependant on product we have different names
        	if($order->id_product == Paypal::to_featured)
        		$item_name = __('Advertisement to featured');
        	else if ($order->id_product == Paypal::to_top)
        		$item_name = __('Advertisement to top');
        	else
        		$item_name = $order->description.__(' category');

			$paypal_url = (Core::config('payment.sandbox')) ? Paypal::url_sandbox_gateway : Paypal::url_gateway;

		 	$paypal_data = array('order_id'            	=> $order_id,
	                             'amount'            	=> number_format($order->amount, 2, '.', ''),
	                             'site_name'        	=> core::config('general.site_name'),
	                             'site_url'            	=> URL::base(TRUE),
	                             'paypal_url'        	=> $paypal_url,
	                             'paypal_account'    	=> core::config('payment.paypal_account'),
	                             'paypal_currency'    	=> core::config('payment.paypal_currency'),
	                             'item_name'			=> $item_name);
			
			$this->template = View::factory('paypal', $paypal_data);
            $this->response->body($this->template->render());
			
		}
		else
		{
			Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->request->redirect(Route::url('default'));
		}
	}

}
