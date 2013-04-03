<?php defined('SYSPATH') or die('No direct access allowed.');

/**
* 
*
* @package    OC
* @category   Widget
* @author     Slobodan <slobodan.josifovic@gmail.com>
* @copyright  (c) 2009-2011 Open Classifieds Team
* @license    GPL v3
*/

class Widgets {
	
	/**  
	 * @var array of widget placeholders in theme
	 */
	public static $placeholder = array();
	
	/**
	 * @var array of widget specific to theme
	 */
	public static $theme_widgets = array();

	/**
	 * @var array of default widgets
	 */
	public static $default_widgets = array();
	
	/**
	 * Gets from conf DB json object of active widgets
	 * @param  string $name_placeholder [name of colling placeholder]
	 * @return [string]                 [widget code]
	 */
	public static function get($name_placeholder)
	{
		$jsonObject = core::config('widget.'.$name_placeholder.'_placeholder');
		
		if($jsonObject)
		{ 
			if(!empty($jsonObject) && $jsonObject !== '[]')
			{
				$active = json_decode($jsonObject, true);
				
				// array of widget path, to include to view
				foreach ($active as $a => $value) 
				{	
					$widget_name = preg_replace('/[0-9]/', '', $value); 
					if(in_array($widget_name, self::$default_widgets) || in_array($widget_name, self::$theme_widgets))
						$active_widgets[$value] = View::factory($widget_name);
					else $active_widgets[$value] = NULL;
					// d($widget_name);
				}

			} else $active_widgets = NULL;

		} else $active_widgets = NULL;
		
		return $active_widgets;
	}

}//end class Widget