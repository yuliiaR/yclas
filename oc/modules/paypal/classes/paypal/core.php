<?php defined('SYSPATH') or die('No direct script access.');
/**
 * PayPal Interface - http://github.com/Austinb/Paypal
 *
 * Uses the key-value pair version of the PayPal API.  This is simpler and easier to implement than soap.
 *
 * PayPal References:
 * 	- https://www.x.com/community/ppx/wpp
 * 	- https://www.x.com/docs/DOC-1374
 *
 * Requrements:
 *  - PHP 5.3+
 *  - cURL (http://www.php.net/manual/en/curl.installation.php)
 *  - PayPal API Account & Information
 *
 * @author Austin Bischoff <austin(dot)bischoff(at)gmail(dot)com>
 */
class Paypal_Core {

	// Release version and codename
	const VERSION  = '1.0';
	const CODENAME = 'ForGreatJustice'; // Oh you know why...

	/*
	 * Settings constants for the config file.
	 */
	const SETTING_API_VERSION 	= 'api_version';
	const SETTING_API_ENDPOINT 	= 'api_endpoint';
	const SETTING_API_USERNAME 	= 'api_username';
	const SETTING_API_PASSWORD 	= 'api_password';
	const SETTING_API_SIGNATURE = 'api_signature';
	const SETTING_PAYPAL_URL 	= 'paypal_url';

	/*
	 * API return msgs.  Set with the ACK key.
	 */
	const RETURN_FAILURE = 'Failure';
	const RETURN_SUCCESS = 'Success';
	const RETURN_SUCCESSWITHWARNING = 'SuccessWithWarning';

	const RETURN_L_ERRORCODE = 'L_ERRORCODE';
	const RETURN_L_SHORTMESSAGE = 'L_SHORTMESSAGE';
	const RETURN_L_LONGMESSAGE = 'L_LONGMESSAGE';
	const RETURN_L_SEVERITYCODE = 'L_SEVERITYCODE';

	/*
	 * Class Error Constants
	 */
	const ERROR_NONE = 0;
	const ERROR_MISSINGCURL = 1;
	const ERROR_CURLCMDFAILED = 2;
	const ERROR_CURLRESPONSEINVALID = 3;

	/*
	 * Command and Action constants
	 */
	const DIRECTPAYMENT = 'DoDirectPayment';
	const SALE = 'Sale';
	const AUTH = 'Authorization';

	const CURRENCY_USD = 'USD';
	const CURRENCY_GBP = 'GBP';

	/*
	 * Card type constants
	 */
	const CARDTYPE_VISA = 'Visa';
	const CARDTYPE_AMEX = 'Amex';
	const CARDTYPE_DISCOVER = 'Discover';
	const CARDTYPE_MASTERCARD = 'MasterCard';

	/*
	 * Key constants for the key-value pairings.
	 */
	const ACK = 'ACK';
	const TRANSACTIONID = 'TRANSACTIONID';
	const AVSCODE = 'AVSCODE';
	const CVV2MATCH = 'CVV2MATCH';
	const RETURNFMFDETAILS = 'RETURNFMFDETAILS';
	const IPADDRESS = 'IPADDRESS';
	const METHOD = 'METHOD';
	const API_VERSION = 'VERSION';
	const PWD = 'PWD';
	const USER = 'USER';
	const SIGNATURE = 'SIGNATURE';
	const PAYMENTACTION = 'PAYMENTACTION';

	const CREDITCARDTYPE = 'CREDITCARDTYPE';
	const ACCT = 'ACCT';
	const EXPDATE = 'EXPDATE';
	const CVV2 = 'CVV2';
	const AMT = 'AMT';

	const FIRSTNAME = 'FIRSTNAME';
	const LASTNAME = 'LASTNAME';
	const EMAIL = 'EMAIL';
	const STREET = 'STREET';
	const STREET2 = 'STREET2';
	const CITY = 'CITY';
	const STATE = 'STATE';
	const ZIP = 'ZIP';
	const COUNTRYCODE = 'COUNTRYCODE';
	const CURRENCYCODE = 'CURRENCYCODE';
	const PHONENUM = 'PHONENUM';


	/**
	 * Holds the errors (if any) from the previous transaction.
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * The config settings to load from within the config file.
	 *
	 * @var string
	 */
	protected $config_name = 'default';

	/**
	 * The loaded config settings.
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * List of valid card types accepted.  Will be limited until the code is completed.
	 *
	 * @var array
	 */
	protected $valid_card_types = array(
		self::CARDTYPE_VISA,
		self::CARDTYPE_AMEX,
		self::CARDTYPE_DISCOVER,
		self::CARDTYPE_MASTERCARD,
	);

	/**
	 * Defines whether or not to return the fraud management result as part of the request.
	 *
	 * @var bool
	 */
	protected $fraud_result = true;

	/**
	 * Create a new instance of the PayPal class with the specified config settings.
	 *
	 * @param string $config
	 */
	static public function factory($config=false)
	{
		if($config !== false)
		{
			$this->config_name = $config;
		}

		return new self();
	}

	public function __construct()
	{
		if(!function_exists('curl_init'))
		{
			throw new PaypalException('cURL is not installed.  See: http://www.php.net/manual/en/curl.installation.php.', self::ERROR_MISSINGCURL);
			return false;
		}

		// Load up the config
		$this->config = Kohana::config('paypal')->{$this->config_name};
	}

	protected function _hasErrors($data=array())
	{
		if($data[self::ACK] == self::RETURN_FAILURE)
		{
			$err = 0;

			// We need to loop until we run out of errors.
			while(true)
			{
				// Check to see if we have error msg defined.
				if(!isset($data[self::RETURN_L_ERRORCODE . $err]))
				{
					break;
				}

				// Add the error to the list.
				$this->errors[$data[self::RETURN_L_ERRORCODE . $err]] = array(
					self::RETURN_L_ERRORCODE => $data[self::RETURN_L_ERRORCODE . $err],
					self::RETURN_L_SHORTMESSAGE => $data[self::RETURN_L_SHORTMESSAGE . $err],
					self::RETURN_L_LONGMESSAGE => $data[self::RETURN_L_LONGMESSAGE . $err],
					self::RETURN_L_SEVERITYCODE => $data[self::RETURN_L_SEVERITYCODE . $err],
				);

				$err++;
			}

			return true;
		}

		return false;
	}

	protected function _formatItems(&$data=array())
	{
		// Correct the amount if it is set.
		if(isset($data[self::AMT]))
		{
			$data[self::AMT] = number_format($data[self::AMT], 2, '.', ','); // Add proper formatting and make a string.
		}

		// Correct the accnt number if it is set.
		if(isset($data[self::ACCT]))
		{
			$data[self::ACCT] = str_replace(array(' ', '-'), '', $data[self::ACCT]); // Replace any bad account chars
		}
	}

	protected function _remoteCall($data=array())
	{
		// Lets add in the auth parts of the code
		$data[self::API_VERSION] = $this->config[self::SETTING_API_VERSION];
		$data[self::PWD] = $this->config[self::SETTING_API_PASSWORD];
		$data[self::USER] = $this->config[self::SETTING_API_USERNAME];
		$data[self::SIGNATURE] = $this->config[self::SETTING_API_SIGNATURE];
		$data[self::RETURNFMFDETAILS] =	(int)$this->fraud_result;

		// Lets make sure the ip has been set.
		if(!isset($data[self::IPADDRESS]) || empty($data[self::IPADDRESS]))
		{
			$data[self::IPADDRESS] = Request::$client_ip; // We pull the ip address from the request.
		}

		// Lets do some formatting and corrections on the data passed.
		$this->_formatItems($data);

		// Init cURL
		$ch = curl_init();

		// Set the cURL options
		curl_setopt_array($ch, array(
			CURLOPT_URL => $this->config[self::SETTING_API_ENDPOINT],
			CURLOPT_VERBOSE => true,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,

			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($data),

			// Disable verification of SSL certificate we are using for connection.
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
		));

		// @todo: Add proxy stuff.

		// Now run the command
		if(!$result = curl_exec($ch))
		{
			throw new PaypalException('Unable to complete cURL request.  Error: '.curl_error($ch), self::ERROR_CURLCMDFAILED);
			return false;
		}

		// Close the cURL connection.
		curl_close($ch);

		// Parse the result into an array so we can do something with it.
		parse_str($result, $return);

		// Check for errors
		if($this->_hasErrors($return) === true)
		{
			return false;
		}

		// Return the payload.
		return $return;
	}

	/**
	 * Return the list of errors that occured.
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Attempt a direct payment using credit card information.
	 *
	 * @param array $data
	 */
	public function doDirectPayment($data=array())
	{
		// Set to direct payment
		$data[self::METHOD] = self::DIRECTPAYMENT;

		// Return the result from the call.
		return $this->_remoteCall($data);
	}
}

/**
 * Thrown when Paypal returns an exception.
 */
class PaypalException extends Exception
{
	public function __construct($message, $code=0)
	{
		return parent::__construct('PayPal: '. $message, $code);
	}
}