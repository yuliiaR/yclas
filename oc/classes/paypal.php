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
     * Validate an IPN request that has been recieved.
     *
     */
    public static function validate_ipn()
    {
        // lets prepend the command to the data we need to verify.
        $data_send = array_merge(array('cmd', '_notify-validate'), $_POST);

        email::send(Core::config('common.email'),Core::config('common.email'),'paypal ipn'.time(),'<br />'.print_r($data_send,true));


        if (core::config('paypal.sandbox'))
            $ipn_url  = self::ipn_sandbox_url;
        else
            $ipn_url  = self::ipn_url;

        // Init cURL
        $ch = curl_init($ipn_url);

        //https://github.com/Austinb/Paypal/blob/master/classes/paypal/ipn.php
        //https://www.x.com/developers/PayPal/documentation-tools/code-sample/216623

        // Set the cURL options
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_send));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        // Now run the command
        if(!$result = trim(curl_exec($ch)))
        {
            Kohana::$log->add(Log::ERROR, 'Paypal connection error');
            return FALSE;
        }

        // Close the cURL connection.
        curl_close($ch);

        // Now lets check the result.
        if($result == 'VERIFIED')
        {
            return TRUE;
        }
        // Verfication result was invalid.  Log it.
        elseif($result == 'INVALID')
        {
            //Kohana::$log->add(Log::ERROR, 'Paypal invalid payment error. Result: '.$result.' Data: '. json_encode($_POST));
            return FALSE;
        }
        // Unknown result. Log it.
        else
        {
            //Kohana::$log->add(Log::ERROR, 'Unknown result from IPN verification. Result: '.$result.' Data: '. json_encode($_POST));
            return FALSE;
        }
    }




    /**
     * For IPN validation
     */
    /*const ipn_sandbox_url      = 'ssl://www.sandbox.paypal.com';
    const ipn_url            = 'ssl://www.paypal.com';
    const ipn_sandbox_host   = 'www.sandbox.paypal.com';
    const ipn_host           = 'www.paypal.com';*/
    

    /**
     *
     * validates the IPN
     */
    public static function validate_ipn_old()
    {   
        if (core::config('paypal.sandbox'))
        {
            $url  = self::ipn_sandbox_url;
            $host = self::ipn_sandbox_host;
        } 
        else
        {
            $url  = self::ipn_url;
            $host = self::ipn_host;
        } 

        $result = FALSE;

        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) 
        {
            $value = urlencode(stripslashes($value));
            
            if($key=='sess' || $key=='session') continue;
                $req .= '&'.$key.'='.$value;
        }

        $header  = 'POST /cgi-bin/webscr HTTP/1.1\r\n';
        $header .= 'Content-Type: application/x-www-form-urlencoded\r\n';
        $header .= 'Host: '.$host.'\r\n'; 
        $header .= 'Content-Length: ' . strlen($req) . '\r\n';
        $header .= 'Connection: close\r\n\r\n';

        $fp = fsockopen ($url, 443, $errno, $errstr, 60);
        
        if (!$fp) 
        {
            Kohana::$log->add(Log::ERROR, 'Paypal connection error');
        } 
        else 
        {
            fputs($fp, $header . $req);
            
            while (!feof($fp)) 
            {
                $res = fgets ($fp, 1024);
            
                if (stripos($res, 'VERIFIED') !== FALSE) 
                {
                    $result = TRUE;
                }
                else if (stripos($res, 'INVALID') !== FALSE) 
                {
                    Kohana::$log->add(Log::ERROR, 'INVALID payment');
                }
            }
            fclose ($fp);
        }
        return $result;
    }


    /**
     * returns allowed Paypal currencies
     * @return array currencies
     */
	public static function get_currency()
	{
		return array(
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

	}
}