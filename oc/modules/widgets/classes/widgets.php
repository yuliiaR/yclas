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

class Widgets {
	

	/**  
	 * @var array of widget placeholders in theme, @ /themes/THEMENAME/init.php
	 */
	public static $theme_placeholders = array();

	/**
	 * @var array of default placeholders, @ /modules/widgets/init.php
	 */
	public static $default_placeholders = array();
	
	/**widgets	 * @var array of widget specific to theme, @ /themes/THEMENAME/init.php
	 */
	public static $theme_widgets = array();

	/**
	 * @var array of default widgets, @ /modules/widgets/init.php
	 */
	public static $default_widgets = array();
	
	/**
	 * Gets from conf DB json object of active widgets
	 * @param  string $name_placeholder name of placeholder
	 * @param bool $only_names, returns only an array with the widgets names
	 * @return array widgets
	 */
	public static function get($name_placeholder, $only_names = FALSE)
	{
		$widgets = array();

		$active_widgets = core::config('placeholder.'.$name_placeholder);

		if($active_widgets!==NULL AND !empty($active_widgets) AND $active_widgets !== '[]')
		{ 
			
			$active_widgets = json_decode($active_widgets, TRUE);
			
			// array of widget path, to include to view
			foreach ($active_widgets as $widget_name) 
			{	
				if ($only_names)
				{
					$widgets[] = $widget_name;
				}
				else
				{
					//search for widget config
					//d('widget.'.$widget_name);
					$widget_data = core::config('widget.'.$widget_name);
					//d($widget_data);
					//found and with data!
					if($widget_data!==NULL AND !empty($widget_data) AND $widget_data !== '[]')
					{ 
						$widget_data = json_decode($widget_data, TRUE);
						
						//creating an instance of that widget
						$widget = new $widget_data['class'];
						//populate the data we got
						$widget->load($widget_name, $widget_data);

						$widgets[] = $widget;
						
					}
				}
				
				
			}//end for

		} //end if widgets
		
		
		return $widgets;
	}

	/**
	 * returns all the widgets 
	 * @param bool $only_names, returns only an array with the widgets names, if not array with widgets instances
	 * @return array 
	 */
	public static function get_widgets($only_names = FALSE)
	{
		$widgets = array();

		$list = array_unique(array_merge(widgets::$default_widgets, widgets::$theme_widgets));
		if ($only_names)
			return $list;

		 //creating an instance of each widget
        foreach ($list as $widget_name) 
			$widgets[] = new $widget_name;


        return $widgets;
	}

	/**
	 * returns placeholders names + widgets
	 * @param bool $only_names, returns only an array with the placeholders names, if not array with widgets instances
	 * @return array 
	 */
	public static function get_placeholders($only_names = FALSE)
	{
		$placeholders = array();

		$list = array_unique(array_merge(widgets::$default_placeholders, widgets::$theme_placeholders));

		//This is a forced placeholders for those widgets that we don't want to lose.
		$list[] = 'inactive';

		if ($only_names)
			return $list;

		//get the widgets for the placeolders
        foreach ($list as $placeholder) 
        	$placeholders[$placeholder] = widgets::get($placeholder);

        return $placeholders;
        
	}

}//end class Widget