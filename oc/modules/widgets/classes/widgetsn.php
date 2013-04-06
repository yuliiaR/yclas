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
	public static $theme_placeholders = array();

	/**
	 * @var array of default placeholders, @ /modules/widgets/init.php
	 */
	public static $default_placeholders = array();
	
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
	public static function get($name_placeholder, $form = FALSE)
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

					if (!$form)
						$widgets[] = $widget->render();
					elseif ($form)
						$widgets[] = $widget->form();
				}

		
			}

		} 
		
		
		return $widgets;
	}

	/**
	 * returns widgets names 
	 * @return array 
	 */
	public static function get_widgets()
	{
		return array_unique(array_merge(Widgetsn::$default_widgets, Widgetsn::$theme_widgets));
	}

	/**
	 * returns placeholders names + widgets
	 * @return array 
	 */
	public static function get_placeholders()
	{
		return array_unique(array_merge(Widgetsn::$default_placeholders, Widgetsn::$theme_placeholders));
	}

}//end class Widget