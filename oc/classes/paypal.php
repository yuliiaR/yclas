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
     * for form generation
     */
    const url_sandbox_gateway    = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    const url_gateway            = 'https://www.paypal.com/cgi-bin/webscr';

    /**
     * For IPN validation
     */
    const ipn_sandbox_url      	= 'ssl://www.sandbox.paypal.com';
    const ipn_url              	= 'ssl://www.paypal.com';
    const ipn_sandbox_host   	= 'www.sandbox.paypal.com';
    const ipn_host              = 'www.paypal.com';

	/**
	*
	* validates the IPN
	*/
	public static function validate_ipn()
	{	
Email::sendEmailFile("slobodan.josifovic@gmail.com",'qwe','end'.'died !fp',"reply",'replyName', NULL);
	d('');
		if (core::config('paypal.sandbox')) 
		{
			$url = self::ipn_sandbox_url;
			$host = self::ipn_sandbox_host;
		}
		else 
		{
			$url = self::ipn_url;
			$host = self::ipn_host;
		}
		$result = FALSE;

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		foreach ($_REQUEST as $key => $value) 
		{
			$value = urlencode(stripslashes($value));
			
			if($key=="sess" || $key=="session") continue;
				$req .= "&$key=$value";
		}
		$header = '';
		$header .= 'POST /cgi-bin/webscr HTTP/1.1\r\n';
		$header .= 'Content-Type: application/x-www-form-urlencoded\r\n';
		$header .= 'Host: '.$host.'\r\n';  
		$header .= 'Content-Length: ' . strlen($req) . '\r\n';
		$header .= 'Connection: close\r\n\r\n';


		$fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 60);
		
		if (!$fp) 
		{
			//error email
			// paypalProblem('Paypal connection error'); // @TODO -- see implementation of this
			// email::send("slobodan.josifovic@gmail.com",'OPENCLASS','!$fp'.'<br />'.$fp.'<br />', '123');
			Kohana::$log->add(Log::ERROR, 'Paypal connection error');
Email::sendEmailFile("slobodan.josifovic@gmail.com",'qwe',$url.$errno.$fp.'died !fp',"reply",'replyName', NULL);
	d('');
		} 
		else 
	 	{
			fputs ($fp, $header . $req);
			
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
					Kohana::$log->add(Log::ERROR, "INVALID payment");

				}
			}
			fclose ($fp);
			
		}
		return $result;
	}

	public static function get_currency()
	{
		$currency_codes=array(
						'Australian Dollars'								=>  'AUD',
						'Canadian Dollars' 									=>	'CAD',
						'Euros' 											=>	'EUR',
						'Pounds Sterling' 									=>	'GBP',
						'Yen' 												=>	'JPY',
						'U.S. Dollars' 										=>	'USD',
						'New Zealand Dollar' 								=>	'NZD',
						'Swiss Franc' 										=>	'CHF',
						'Hong Kong Dollar' 									=>	'HKD',
						'Singapore Dollar' 									=>	'SGD',
						'Swedish Krona' 									=>	'SEK',
						'Danish Krone' 										=>	'DKK',
						'Polish Zloty' 										=>	'PLN',
						'Norwegian Krone' 									=>	'NOK',
						'Hungarian Forint' 									=>	'HUF',
						'Czech Koruna' 										=>	'CZK',
						'Israeli Shekel' 									=>	'ILS',
						'Mexican Peso' 										=>	'MXN',
						'Brazilian Real (only for Brazilian users)' 		=>	'BRL',
						'Malaysian Ringgits (only for Malaysian users)'		=>	'MYR',
						'Philippine Pesos' 									=>	'PHP',
						'Taiwan New Dollars' 								=>	'TWD',
						'Thai Baht' 										=>	'THB'
		);

		return $currency_codes;

	}
}