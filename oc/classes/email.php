<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simple email class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */

class Email {
	
	/**
	 * Simple function to send an email
	 *
	 * @param string $to
	 * @param string $from
	 * @param string $subject
	 * @param string $body
	 * @param string $extra_header
	 * @return boolean
	 */
	public static function send($to,$from,$subject,$body,$headers=NULL)
	{
		
		if ($headers==NULL)
		{
			$headers = 'MIME-Version: 1.0' . PHP_EOL;
			$headers.= 'Content-type: text/html; charset=utf8'. PHP_EOL;
			$headers.= 'From: '.$from.PHP_EOL;
			$headers.= 'Reply-To: '.$from.PHP_EOL;
			$headers.= 'Return-Path: '.$from.PHP_EOL;
			$headers.= 'X-Mailer: PHP/' . phpversion().PHP_EOL;
		}
	
		return mail($to,$subject,$body,$headers);
		
	}

} //en email