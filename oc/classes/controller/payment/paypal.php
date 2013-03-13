<?php defined('SYSPATH') or die('No direct script access.');

/**
* paypal class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@garridodiaz.com>, Slobodan Josifovic <slobodan.josifovic@gmail.com>
* @license GPL v3
*/

class Controller_Payment_Paypal extends Controller{
	
	public function action_ipn()
	{paypal::validate_ipn();
Email::sendEmailFile("slobodan.josifovic@gmail.com",'1111asdasd1','item_number',"reply",'replyName', NULL);
	d('');
		$this->auto_render = FALSE;

		//START PAYPAL IPN
		//manual checks
		$id_order = $_POST['item_number'];
		$paypal_amount = $_POST['mc_gross'];
		$payer_id = $_POST['payer_id'];

		//retrieve info for the item in DB
		$order = new Model_Order();
		$order = $order->where('id_order', '=', $id_order)
					   ->where('status', '=', Model_Order::STATUS_CREATED)
					   ->limit(1)->find();
		
		if($order->loaded())
		{
			// detect product to be processed 
			if (is_numeric($order->id_product))
			{
				$id_category = new Model_Category();
				$id_category = $id_category->where('id_category', '=', $order->id_product)->limit(1)->find();
				$product_id = $id_category->id_category;
			}
			else
			{
				$product_id = $order->id_product;
			} 

			if (	$_POST['mc_gross']==number_format($order->amount, 2, '.', '')
				&&  $_POST['mc_currency']==core::config('paypal.paypal_currency') 
				&& ($_POST['receiver_email']==core::config('paypal.paypal_account') 
					|| $_POST['business']==core::config('paypal.paypal_account')))
			{//same price , currency and email no cheating ;)
//Email::sendEmailFile("slobodan.josifovic@gmail.com",'11111',$_POST['item_number'].' '.'item_number',"reply",'replyName', NULL);
				if (paypal::validate_ipn()) 
				{
					
					$order->confirm_payment($id_order, core::config('general.moderation'));
					//email::send("slobodan.josifovic@gmail.com",'TEST',"test", $_POST['mc_gross']); // debug mode
					
				} //payment succeed and we confirm the post ;) (CALL TO LOGIC PUT IN ctrl AD)

				else
				{
					Email::sendEmailFile("slobodan.josifovic@gmail.com",'qwe','xxxxxxx',"reply",'replyName', NULL);
	d('');
					// Log an invalid request to look into
					// PAYMENT INVALID & INVESTIGATE MANUALY!
					$subject = 'Invalid Payment';
					$message = 'Dear Administrator,<br />
								A payment has been made but is flagged as INVALID.<br />
								Please verify the payment manualy and contact the buyer. <br /><br />Here is all the posted info:';
					//email::send("slobodan.josifovic@gmail.com",$subject,$message.'<br />'.print_r($_POST,true)); // @TODO send email
				}	

			} 
			else //trying to cheat....
			{
				$subject = 'Cheat Payment !?';
				$message = 'Dear Administrator,<br />
							A payment has been made but is flagged as Cheat.<br />
							We suspect some forbiden or illegal actions have been made with this transaction.<br />
							Please verify the payment manualy and contact the buyer. <br /><br />Here is all posted info:';
				//email::send("slobodan.josifovic@gmail.com",$subject,$message.'<br />'.print_r($_POST,true)); // @TODO send email
			}
		}// END order loaded
		else
		{
			//order not loaded
            $subject = 'Order not loaded';
            $message = 'Dear Administrator,<br />
                        Someone is trying to pay an inexistent Order...
                        Please verify the payment manually and contact the buyer. <br /><br />Here is all posted info:';
            email::send(Core::config('common.email'),Core::config('common.email'),$subject,$message.'<br />'.print_r($_POST,true));
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
            ->where('id_user','=',Auth::instance()->get_user()->id_user)
            ->where('status','=',Model_Order::STATUS_CREATED)
            ->limit(1)->find();

        // different product name (message)
        if(is_numeric($order->id_product)) $paypal_msg = core::config('general.paypal_msg_product_category');
        elseif ($order->id_product == 'pay_to_go_on_top') $paypal_msg = core::config('general.paypal_msg_product_to_top');
        else $paypal_msg = core::config('general.paypal_msg_product_to_featured');

        
        if ($order->loaded())
        {

			$paypal_url = (Core::config('paypal.sandbox')) ? Paypal::url_sandbox_gateway : Paypal::url_gateway;
// d($order->amount);
		 	$paypal_data = array('order_id'            	=> $order_id,
	                             'amount'            	=> number_format($order->amount, 2, '.', ''),
	                             'site_name'        	=> core::config('general.site_name'),
	                             'site_url'            	=> core::config('general.site_url'),
	                             'paypal_url'        	=> $paypal_url,
	                             'paypal_account'    	=> core::config('paypal.paypal_account'),
	                             'paypal_currency'    	=> core::config('paypal.paypal_currency'),
	                             'item_name'        	=> $paypal_msg);

			// d($payment_paypal);
			// $development_logic = new Model_Order();
			// $development_logic->confirm_payment($order_id, core::config('general.moderation'));
			$this->template->content = View::factory('paypal', $paypal_data); //@TODO -- make this active when paypal active
			$this->response->body($this->template->render());
		}
		else
		{
			Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->request->redirect(Route::url('default'));
		}
	}

}