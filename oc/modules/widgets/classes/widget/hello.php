<?php defined('SYSPATH') or die('No direct access allowed.');

class Widget_Hello extends Widget
{
	/**
	 * $name
	 * Name of placeholder
	 * @var string
	 */
	public static $title = 'hello';

	/**
	 * $deactivate_placeholder 
	 * limit placeholders for this widget 
	 * (leave empty array for NO restrictions )
	 * 
	 * @var array
	 */
	public static $deactivate_placeholder = array('footer');

	public function set_info()
	{
		$this->$title = 'hello';
		$this->$short_description = __('Hello world is our first widget');
	}

	public static function get_info()
	{
		
		return array('short_description' 		=>__('Hello world is our first widget'),
					 'title'					=>self::$title, 
					 'deactivate_placeholder'	=>self::$deactivate_placeholder);
	}


}