<?php defined('SYSPATH') or die('No direct script access.');

/**
* paypal class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@garridodiaz.com>
* @license GPL v3
*/

class Controller_Payment_Paypal extends Controller{
	
	public function action_ipn()
	{
		//START PAYPAL IPN
		//manual checks
		$idItem = $_POST['item_number'];
		$paypal_amount = $_POST['mc_gross'];
		$payer_id = $_POST['payer_id'];

		//retrieve info for the item in DB
		$query = new Model_Order();
		$query = $query->where('id_order', '=', $idItem)
					   ->where('status', '=', 0)
					   ->limit(1)->find();
		
		// detect product to be processed 
		if ($query->loaded() && is_numeric($query->id_product))
		{
			$id_category = new Model_Category();
			$id_category = $id_category->where('id_category', '=', $query->id_product)->limit(1)->find();
			$product_id = $id_category->id_category;
		}
		else
		{
			$product_id = $query->id_product;
		} 
		
		$amount = $query->amount; // product amount
		
		
		

		if ($_POST['mc_gross']==$amount 
				&& $_POST['mc_currency']==core::config('paypal.paypal_currency') 
				&& ($_POST['receiver_email']==core::config('paypal.paypal_account') 
					|| $_POST['business']==core::config('paypal.paypal_account')))
		{//same price , currency and email no cheating ;)

			if (paypal::validate_ipn()) 
			{
				$confirm = new Model_Ad();
				$confirm = $confirm->confirm_payment($idItem, core::config('general.moderation'));
				email::send("slobodan.josifovic@gmail.com",'TEST',"test", $_POST['mc_gross']); // debug mode
				
			} //payment succeed and we confirm the post ;) (CALL TO LOGIC PUT IN ctrl AD)

			else
			{
				
				// Log an invalid request to look into
				// PAYMENT INVALID & INVESTIGATE MANUALY!
				$subject = 'Invalid Payment';
				$message = 'Dear Administrator,<br />
							A payment has been made but is flagged as INVALID.<br />
							Please verify the payment manualy and contact the buyer. <br /><br />Here is all the posted info:';
				//email::send("slobodan.josifovic@gmail.com",$subject,$message.'<br />'.print_r($_POST,true));
			}	

		} 
		else
		{
			$subject = 'Cheat Payment !?';
			$message = 'Dear Administrator,<br />
						A payment has been made but is flagged as Cheat.<br />
						We suspect some forbiden or illegal actions have been made with this transaction.<br />
						Please verify the payment manualy and contact the buyer. <br /><br />Here is all posted info:';
			//email::send("slobodan.josifovic@gmail.com",$subject,$message.'<br />'.print_r($_POST,true));
		}
		//trying to cheat....
	}

	public function action_form()
	{ 
		$order_id = $this->request->param('order_id');
		$paypal_msg = $this->request->param('paypal_msg');

		$payment_paypal = paypal::payment($order_id, $paypal_msg);
		// d($payment_paypal);
		// $development_logic = new Model_Ad();
		// $development_logic->confirm_payment($order_id, $payer_id, core::config('general.moderation'));
		$this->template->content = View::factory('paypal', $payment_paypal); //@TODO -- make this active when paypal active
	}

}