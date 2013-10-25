<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simple email class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>, Slobodan <slobodan.josifovic@gmail.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */


class HybridAuth {

	public static function Initialize()
	{
		require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Auth','php');
		
		$config = core::config('general.social_auth');

		$hybridauth = new Hybrid_Auth($config);
	}
}