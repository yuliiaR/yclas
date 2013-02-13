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
	
	public static function ipn()
	{
		//START PAYPAL IPN
		
		//manual checks
		$idItem = cP('item_number');
		if (!is_numeric($idItem)) paypal::report_problem('PayPal IPN: not any item ID (item_number is not numeric).');

		//retrieve info for the item in DB
		$query="select password,idCategory
				from ".TABLE_PREFIX."posts p
				where idPost=$idItem and isConfirmed=0 Limit 1";

		$post_password='';
		$idCategory=0;

		$post_result=$ocdb->query($query);
		if (mysql_num_rows($post_result))
		{
			$post_row=mysql_fetch_assoc($post_result);

			$post_password=$post_row["password"];
			$idCategory=$post_row["idCategory"];
		}
		else paypal::report_problem('Could not find the Item in DB.');//not found

		$amount = (float)PAYPAL_AMOUNT;
		if (PAYPAL_AMOUNT_CATEGORY)
		{
			$query="select price from ".TABLE_PREFIX."categories where idCategory=$idCategory Limit 1";
		    	
		    	$result=$ocdb->query($query);
		    	if (mysql_num_rows($result))
		    	{
					$row=mysql_fetch_assoc($result);

				if (is_numeric($row["price"]))
					if ((float)$row["price"] != 0)
						$amount=(float)$row["price"];
				}	
		}

		if ((float)cP('mc_gross')==$amount && cP('mc_currency')==PAYPAL_CURRENCY && (cP('receiver_email')==PAYPAL_ACCOUNT || cP('business')==PAYPAL_ACCOUNT))
		{//same price , currency and email no cheating ;)

			if (paypal::validate_ipn()) confirmPost($idItem,$post_password); //payment succeed and we confirm the post ;)

			else
			{
			
				// Log an invalid request to look into
				// PAYMENT INVALID & INVESTIGATE MANUALY!
				$subject = 'Invalid Payment';
				$message = 'Dear Administrator,<br />
							A payment has been made but is flagged as INVALID.<br />
							Please verify the payment manualy and contact the buyer. <br /><br />Here is all the posted info:';
				sendEmail(PAYPAL_ACCOUNT,$subject,$message.'<br />'.print_r($_POST,true));
			}	

		}
		//trying to cheat....
		else paypal::report_problem('Trying to fake the post data');
			
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
}