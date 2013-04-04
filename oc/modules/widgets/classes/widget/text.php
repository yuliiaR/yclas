<?php defined('SYSPATH') or die('No direct access allowed.');

class Widget_Text extends Widget
{

	/**
	 * $name
	 * Name of placeholder
	 * @var string
	 */
	public static $title = 'text';

	

	public static function get_info()
	{
		
		return array('short_description' 		=>__('This is simple text'),
					 'title'					=>self::$title, 
					 'deactivate_placeholder'	=>self::$deactivate_placeholder);
	}
}