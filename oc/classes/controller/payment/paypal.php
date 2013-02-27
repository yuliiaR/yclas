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

			if ($this->validate_ipn()) 
			{
				$confirm = new Model_Ad();
				$confirm = $confirm->confirm_payment($idItem, $payer_id, core::config('general.moderation'));
				
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


	/**
	*
	* validates the IPN
	*/
	public static function validate_ipn()
	{	
		if (core::config('paypal.sandbox')) $URL='ssl://www.sandbox.paypal.com';
		else $URL='ssl://www.paypal.com';
		$result = FALSE;

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		foreach ($_REQUEST as $key => $value) 
		{
			$value = urlencode(stripslashes($value));
			
			if($key=="sess" || $key=="session") continue;
				$req .= "&$key=$value";
		}

		$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Host: www.sandbox.paypal.com\r\n";  // @TODO WHEN GO PRODUCTION -> www.paypal.com for a live site
		$header .= "Content-Length: " . strlen($req) . "\r\n";
		$header .= "Connection: close\r\n\r\n";

		$fp = fsockopen ($URL, 443, $errno, $errstr, 60);
		
		if (!$fp) {
			//error email
			// paypalProblem('Paypal connection error'); // @TODO -- see implementation of this
			email::send("slobodan.josifovic@gmail.com",'OPENCLASS','!$fp'.'<br />'.$fp.'<br />', '123');
		} 
		else 
		{
			fputs($fp, $header . $req);
			
			while (!feof($fp)) 
			{
				$res = fgets ($fp, 1024);
			
				if (stripos($res, "VERIFIED") !== FALSE) 
				{
					$result = TRUE;
					email::send("slobodan.josifovic@gmail.com",'OPENCLASS','VERIFIED'.'<br />', '123');
				}
				else if (stripos($res, "INVALID") !== FALSE) 
				{
					//log the error in some system?
					//paypalProblem('Invalid payment');
					email::send("slobodan.josifovic@gmail.com",'OPENCLASS','INVALID'.'<br />', '123');
				}
			}
			fclose ($fp);
		}
		email::send("slobodan.josifovic@gmail.com",'OPENCLASS','RESULT'.'<br />'.$result, '123');

		// email::send($admin->email,$message['email_from'],$message['subject'],$message['message']);
		return $result;
	}


	/**
	 * [returns all necessary data to form]
	 * @param  [string] $idItem  [time id]
	 * @param  [string] $product [recongnises product type]
	 * @return [array]          [data to be send to paypal]
	 */
	public static function payment($idItem, $payer_id)
	{
		// fields / values to be sent 

		$idItem; // product id 
		$amount; // amount of product
		$site_name; // name of the website 
		$site_url; // url to be send back to 
		$sendbox; // sendbox TRUE/FALSE
		$paypal_account; // account of business 
		$paypal_currency; // currency of paypal (can be different than currency of site) example "USD"

		$amount = new Model_Order();
		$amount = $amount->where('id_order', '=', $idItem)->limit(1)->find();
		$amount = $amount->amount;


		if($amount != 0)
		{
			$paypal_info = core::config('paypal');
			
			foreach ($paypal_info as $si => $value) 
			{
				if($si == 'sandbox')
				{
					$sandbox = $value;
				}
				elseif ($si == 'paypal_currency')
				{
					$paypal_currency = $value;
				}
				elseif ($si == 'paypal_account') 
				{
					$paypal_account = $value;	
				}
			}

			$general_info = core::config('general');
			
			foreach ($general_info as $gi => $value) {
				if ($gi == 'site_name') 
				{
					$site_name = $value;
				}
				elseif ($gi == 'site_url') 
				{
					$site_url = $value;
				}
			}

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