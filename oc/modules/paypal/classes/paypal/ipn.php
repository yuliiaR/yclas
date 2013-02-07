<?php defined('SYSPATH') or die('No direct script access.');
/**
 * PayPal IPN Interface - http://github.com/Austinb/Paypal
 *
 * Used to validate and convert IPN notifications.
 *
 * PayPal References:
 * - https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_IPNandPDTVariables
 *
 * Requrements:
 *  - PHP 5.3+
 *  - cURL (http://www.php.net/manual/en/curl.installation.php)
 *  - PayPal API Account & Information
 *
 * @author Austin Bischoff <austin(dot)bischoff(at)gmail(dot)com>
 */
class Paypal_IPN extends Paypal
{

	const SETTING_IPN_VERIFY_PATH = 'ipn_verify';

	/*
	 * Constants for the verifcation status.
	 */
	const STATUS_VERIFIED = 'VERIFIED';
	const STATUS_INVALID = 'INVALID';

	/*
	 * Constants for status codes.
	 */
	const PAYMENT_STATUS_CANCELEDREVERSAL = 'Canceled_Reversal';
	const PAYMENT_STATUS_COMPLETED = 'Completed';
	const PAYMENT_STATUS_CREATED = 'Created';
	const PAYMENT_STATUS_DEINED = 'Denied';
	const PAYMENT_STATUS_EXPIRED = 'Expired';
	const PAYMENT_STATUS_FAILED = 'Failed';
	const PAYMENT_STATUS_PENDING = 'Pending';
	const PAYMENT_STATUS_REFUNDED = 'Refunded';
	const PAYMENT_STATUS_REVERSED = 'Reversed';
	const PAYMENT_STATUS_PROCESSED = 'Processed';
	const PAYMENT_STATUS_VOIDED = 'Voided';

	/*
	 * Constants for reason codes.  Only used when payment_status is Reversed, Refunded, or Canceled_Reversal.
	 */
	const REASON_CODE_ADJUSTMENT_REVERSAL = 'adjustment_reversal';
	const REASON_CODE_COMPLAINT = 'buyer-complaint';
	const REASON_CODE_CHARGEBACK = 'chargeback';
	const REASON_CODE_CHARGEBACK_REIMBURSEMENT = 'chargeback_reimbursement';
	const REASON_CODE_CHARGEBACK_SETTLEMENT = 'chargeback_settlement';
	const REASON_CODE_GUARANTEE = 'guarantee';
	const REASON_CODE_REFUND = 'refund';
	const REASON_CODE_OTHER = 'other';


	/**
	 * Validate an IPN request that has been recieved.
	 *
	 * @param array $data
	 * @throws PaypalException
	 */
	public function validate(Array $data=null)
	{
		// We have no data, so nothing to verify.
		if(empty($data))
		{
			return false;
		}

		// Now lets prepend the command to the data we need to verify.
		$data_send = array_merge(array('cmd', '_notify-validate'), $data);

		// Init cURL
		$ch = curl_init();

		// Set the cURL options
		curl_setopt_array($ch, array(
			CURLOPT_URL => $this->config[self::SETTING_IPN_VERIFY_PATH],
			CURLOPT_VERBOSE => true,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,

			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($data_send),

			// Disable verification of SSL certificate we are using for connection.
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
		));

		// @todo: Add proxy stuff.

		// Now run the command
		if(!$result = trim(curl_exec($ch)))
		{
			throw new PaypalException('Unable to complete cURL request.  Error: '.curl_error($ch), self::ERROR_CURLCMDFAILED);
			return false;
		}

		// Close the cURL connection.
		curl_close($ch);

		// Now lets check the result.
		if($result == self::STATUS_VERIFIED)
		{
			return true;
		}
		// Verfication result was invalid.  Log it.
		elseif($result == self::STATUS_INVALID)
		{
			Kohana::$log->add('notice', 'IPN verification result was invalid. Result: '.$result.' Data: '. json_encode($data));
			return false;
		}
		// Unknown result. Log it.
		else
		{
			Kohana::$log->add('notice', 'Unknown result from IPN verification. Result: '.$result.' Data: '. json_encode($data));
			return false;
		}
	}
}