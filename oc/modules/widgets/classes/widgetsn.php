<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper class to display the widgets
 *
 * @package    OC
 * @category   Widget
 * @author     Chema <chema@garridodiaz.com>, Slobodan <slobodan.josifovic@gmail.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Widgetsn {
	
	/**  
	 * @var array of widget placeholders in theme, @ /themes/THEMENAME/init.php
	 */
	public static $placeholders = array();
	
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
	 * @return array widgets html
	 */
	public static function get($name_placeholder)
	{

		$widgets = array();

		$active_widgets = core::config('placeholder.'.$name_placeholder);

		if($active_widgets!==NULL AND !empty($active_widgets) AND $active_widgets !== '[]')
		{ 
			
			$active_widgets = json_decode($active_widgets, TRUE);
			
			// array of widget path, to include to view
			foreach ($active_widgets as $widget_name) 
			{	
				//search for widget config
				$widget_data = core::config('widget.'.$widget_name);

				//found and with data!
				if($widget_data!==NULL AND !empty($widget_data) AND $widget_data !== '[]')
				{ 
					$widget_data = json_decode($widget_data, TRUE);
					
					//creating an instance of that widget
					$widget = new $widget_data['class'];
					//populate the data we got
					$widget->load($widget_name, $widget_data['data']);

					$widgets[] = $widget->render();
				}

		
			}

		} 
		
		
		return $widgets;
	}

}//end class Widget