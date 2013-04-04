<?php defined('SYSPATH') or die('No direct access allowed.');

class Widget_Hello extends Widget
{
	/**
	 * $name
	 * Name of placeholder
	 * @var string
	 */
	public static $title = 'hello';


	public static $fields = array();


	public function __construct()
	{
		self::$fields = array(	  'rss_items' => array( 'type'		=> 'select',
														'label'		=> __('# items to display'),
														'value'     => range(1,10), 
														'required'	=> TRUE),

						 		  'rss_url'  => array( 'type'		=> 'uri',
						 		  						'label'		=> __('items you need'),
														'required'	=> TRUE),);
	}

	/**
	 * $deactivate_placeholder 
	 * limit placeholders for this widget 
	 * (leave empty array for NO restrictions )
	 * 
	 * @var array
	 */
	public static $deactivate_placeholder = array('footer');


	public static function get_info()
	{
		
		return array('short_description' 		=>__('Hello world is our first widget'),
					 'title'					=>self::$title, 
					 'deactivate_placeholder'	=>self::$deactivate_placeholder);
	}


}