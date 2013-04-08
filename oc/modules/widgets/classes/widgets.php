<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper class to display the widgets
 *
 * @package    OC
 * @category   Widget
 * @author     Slobodan <slobodan.josifovic@gmail.com>
 * @copyright  (c) 2009-2011 Open Classifieds Team
 * @license    GPL v3
 */

class Widgets {
	
	/**  
	 * @var array of widget placeholders in theme, @ /themes/THEMENAME/init.php
	 */
	public static $placeholder = array();
	
	/**
	 * @var array of widget specific to theme, @ /themes/THEMENAME/init.php
	 */
	public static $theme_widgets = array();

	/**
	 * @var array of default widgets, @ /modules/widgets/init.php
	 */
	public static $default_widgets = array();
	
	/**
	 * Gets from conf DB json object of active widgets
	 * @param  string $name_placeholder name of placeholder
	 * @return [string]                 [widget code]
	 */
	public static function get($name_placeholder)
	{
		$json_object = core::config('widget.'.$name_placeholder.'_placeholder');
		
		if($json_object)
		{ 
			if(!empty($json_object) && $json_object !== '[]')
			{
				$active = json_decode($json_object, true);
				
				// array of widget path, to include to view
				foreach ($active as $a => $value) 
				{	
					$widget_name = preg_replace('/[0-9]/', '', $value); 
					if(in_array($widget_name, self::$default_widgets) || in_array($widget_name, self::$theme_widgets))
					{
						$active_widgets[$value] = View::factory($widget_name);
					}
					else $active_widgets[$value] = NULL;
					// d($widget_name);
				}

			} 
			else 
				$active_widgets = NULL;

		} 
		else 
			$active_widgets = NULL;
		
		return $active_widgets;
	}

}//end class Widget