<?php defined('SYSPATH') or die('No direct access allowed.');

class Widget_Hello
{

	/**
	 * $name
	 * Name of placeholder
	 * @var string
	 */
	public static $title = 'hello';

	
public static $deactivate_placeholder = array();

	public static function get_info()
	{
		
		return array('short_description' 		=>__('This is simple hello text'),
					 'title'					=>self::$title, 
					 'deactivate_placeholder'	=>self::$deactivate_placeholder);
	}
}