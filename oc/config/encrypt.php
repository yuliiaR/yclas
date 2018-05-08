<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'default' => array(
		'type'   => 'openssl',
        'key'    => '1234567891234567',//not in use, we use auth.php hash_key
		'cipher' => 'AES-128-CBC',
	),

);
