<?php
return array(
	'default' => array(
		// Url the buyer is sent to if using their paypal account.
		'paypal_url' => 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=',

		// The currency to be used in transactions.
		'paypal_currency' => 'USD',

		// The version of the PayPal API.  This is defined by PayPal so do not change this randomly.
		'api_version' => '64.0',

		// The endpoint to use.  Can be test or live, be sure this is correct.
		// Note: If you use test api you MUST use test credentials.  See http://developer.paypal.com and setup a test account.
		'api_endpoint' => 'https://api-3t.sandbox.paypal.com/nvp',

		// The username for the API account.
		'api_username' => 'ChangeMe',

		// The password for the API account.
		'api_password' => 'ChangeMe',

		// The API account signature.
		'api_signature' => 'ChangeMe',

		// IPN verify path.  Can be test or live
		'ipn_verify' => 'http://www.sandbox.paypal.com/cgi-bin/webscr',
	),
);