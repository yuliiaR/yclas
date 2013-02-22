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
		$idItem = $this->request->post('item_number');
		$paypal_amount = $this->request->post('amount');
		$payer_id = $this->request->post('payer_id');

		//retrieve info for the item in DB
		$query = new Model_Order();
		$query = $query->where('id_product', '=', $idItem)
					   ->and_where('status', '=', 0)
					   ->and_where('id_user', '=', $payer_id)
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
			$product_id = $query->product_id;
		} 
		
		$amount = $query->amount; // product amount

		if ((float)$this->request->post('mc_gross')==$amount 
				&& $this->request->post('mc_currency')==core::config('paypal.paypal_currency') 
				&& ($this->request->post('receiver_email')==core::config('paypal.paypal_account') 
					|| $this->request->post('business')==core::config('paypal.paypal_account')))
		{//same price , currency and email no cheating ;)

			if ($this->validate_ipn()) $this->request->redirect(Route::url('ad', array('action'=>'confirm_payment','category'=>$idItem, 'seotitle'=>$payer_id))); //payment succeed and we confirm the post ;) (CALL TO LOGIC PUT IN ctrl AD)

			else
			{
			
				// Log an invalid request to look into
				// PAYMENT INVALID & INVESTIGATE MANUALY!
				$subject = 'Invalid Payment';
				$message = 'Dear Administrator,<br />
							A payment has been made but is flagged as INVALID.<br />
							Please verify the payment manualy and contact the buyer. <br /><br />Here is all the posted info:';
				sendEmail($paypal_account,$subject,$message.'<br />'.print_r($_POST,true));
			}	

		}
		//trying to cheat....
			
	} 


	/**
	*
	* validates the IPN
	*/
	public static function validate_ipn()
	{
		if (PAYPAL_SANDBOX) $URL='ssl://www.sandbox.paypal.com';
		else $URL='ssl://www.paypal.com';
		$result = FALSE;

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		foreach ($_REQUEST as $key => $value) {
			$value = urlencode(stripslashes($value));
			
			if($key=="sess" || $key=="session") continue;
				$req .= "&$key=$value";
		}

		// post back to PayPal system to validate
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen ($URL, 443, $errno, $errstr, 60);

		if (!$fp) {
			//error email
			paypalProblem('Paypal connection error');
		} 
		else 
		{
			fputs ($fp, $header . $req);
			
			while (!feof($fp)) 
			{
				$res = fgets ($fp, 1024);
				
				if (strcmp ($res, "VERIFIED") == 0) 
				{
					$result = TRUE;
				}
				else if (strcmp ($res, "INVALID") == 0) 
				{
					//log the error in some system?
					//paypalProblem('Invalid payment');
				}
			}
			fclose ($fp);
		}
		return $result;
	}


	/**
	 * [returns all necessary data to form]
	 * @param  [string] $idItem  [time id]
	 * @param  [string] $product [recongnises product type]
	 * @return [array]          [data to be send to paypal]
	 */
	public static function payment($idItem, $payer_id ,$product = 'category')
	{
		// fields / values to be sent 

		$idItem; // product id 
		$amount; // amount of product
		$site_name; // name of the website 
		$site_url; // url to be send back to 
		$sendbox; // sendbox TRUE/FALSE
		$paypal_account; // account of business 
		$paypal_currency; // currency of paypal (can be different than currency of site) example "USD"
		$payer_id;

		if($product == 'pay_to_top' || $product == 'pay_to_featured')
		{
			$amount = new Model_Config();
			$amount = $amount->where('config_key', '=', $idItem)->limit(1)->find();
			$amount = $amount->config_value;

		}
		elseif ($product == 'category')
		{
			$amount = new Model_Category();
			$amount = $amount->where('id_category', '=', $idItem)->limit(1)->find();
			$amount = $amount->price;
		}

		// get parent price and update
		if($amount == 0)
		{
			$id_cat_parent = $amount->id_category_parent;
			unset($amount);
			$amount = new Model_Category();
			$amount = $amount->where('id_category', '=', $id_cat_parent)->limit(1)->find();
			$amount = $amount->price;
		}
		

		if($amount != 0)
		{
			$paypal_info = new Model_Config();
			$paypal_info = $paypal_info->where('group_name', '=', 'paypal')
								   ->find_all();

			foreach ($paypal_info as $si) 
			{
				if($si->config_key == 'sandbox')
				{
					$sandbox = $si->config_value;
				}
				elseif ($si->config_key == 'paypal_currency')
				{
					$paypal_currency = $si->config_value;
				}
				elseif ($si->config_key == 'paypal_account') 
				{
					$paypal_account = $si->config_value;	
				}
			}

			$general_info = new Model_Config();
			$general_info = $general_info->where('group_name', '=', 'general')
								   ->find_all();

			foreach ($general_info as $gi) {
				if ($gi->config_key == 'site_name') 
				{
					$site_name = $gi->config_value;
				}
				elseif ($gi->config_key == 'site_url') 
				{
					$site_url = $gi->config_value;
				}
			}



			// unset($paypal_info);

			$paypal_data = array('idItem'			=>$idItem,
								 'amount'			=>$amount,
								 'site_name'		=>$site_name,
								 'site_url'			=>$site_url,
								 'sandbox'			=>$sandbox,
								 'paypal_account'	=>$paypal_account,
								 'paypal_currency'	=>$paypal_currency,
								 'payer_id'			=>$payer_id);
			
			return $paypal_data;
		}
		else return NULL;
	}
}