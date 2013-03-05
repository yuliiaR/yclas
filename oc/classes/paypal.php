<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Paypal class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>, Slobodan <slobodan.josifovic@gmail.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

Class Paypal {
	
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
			// email::send("slobodan.josifovic@gmail.com",'OPENCLASS','!$fp'.'<br />'.$fp.'<br />', '123');
			// @TODO insted of mails for errors make logs
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
				}
				else if (stripos($res, "INVALID") !== FALSE) 
				{
					//log the error in some system?
					//paypalProblem('Invalid payment');
					Kohana::$log->add(Log::ERROR, stripos($res, "INVALID"));
				}
			}
			fclose ($fp);
		}
		return $result;
	}


	/**
	 * [returns all necessary data to create paypal form]
	 * @param  [string] $order_id  [time id]
	 * @param  [string] $product [recongnises product type]
	 * @return [array]          [data to be send to paypal]
	 */
	public static function payment($order_id, $paypal_msg = NULL)
	{
		// fields / values to be sent 

		$order_id; // product id 
		$amount; // amount of product
		$site_name; // name of the website 
		$site_url; // url to be send back to 
		$sendbox; // sendbox TRUE/FALSE
		$paypal_account; // account of business 
		$paypal_currency; // currency of paypal (can be different than currency of site) example "USD"

		$amount = new Model_Order();
		$amount = $amount->where('id_order', '=', $order_id)->limit(1)->find();
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

			if ($sandbox) $paypalWeb = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // TEST SANDBOX
			else $paypalWeb = 'https://www.paypal.com/cgi-bin/webscr';

			$paypal_data = array('order_id'			=>$order_id,
								 'amount'			=>number_format($amount, 2, '.', ''),
								 'site_name'		=>$site_name,
								 'site_url'			=>$site_url,
								 'paypalWeb'		=>$paypalWeb,
								 'paypal_account'	=>$paypal_account,
								 'paypal_currency'	=>$paypal_currency,
								 'paypal_msg'		=>$paypal_msg);
			
			return $paypal_data;
		}
		else return NULL;
	}
	
	public static function make_order($res)
	{

		//create order		
		$order = new Model_Order();

		$order->id_user = $res['id_user'];
		$order->id_ad = $res['id_ad'];
		$order->id_product = $res['id_product'];
		$order->paymethod = $res['paymethod'];
		$order->currency = $res['currency'];
		$order->amount = $res['amount'];

		try 
		{
			$order->save();
		} 
		catch (Exception $e){
			Kohana::$log->add(Log::ERROR, Kohana_Exception::text($e));
		} 

		// find correct order to make paypal invoice 
		$order_id = new Model_Order();
		$order_id = $order_id->where('id_ad','=',$res['id_ad'])
							 ->where('status','=',0)
							 ->where('id_user','=',$res['id_user'])
							 ->where('id_product', '=', $res['id_product'])
							 ->order_by('id_order', 'desc')
							 ->limit(1)->find();
		$order_id = $order_id->id_order; 

		return $order_id;
	}
}