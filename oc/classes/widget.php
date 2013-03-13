<?php defined('SYSPATH') or die('No direct access allowed.');

/**
* Extended functionality for Kohana View
*
* @package    OC
* @category   Widget
* @author     Slobodan <slobodan.josifovic@gmail.com>
* @copyright  (c) 2009-2011 Open Classifieds Team
* @license    GPL v3
*/

class Widget {

	/**
	 * Gets from conf DB json object of active widgets
	 * @param  string $name_placeholder [name of colling placeholder]
	 * @return [string]                   [widget code]
	 */
	public static function get($name_placeholder = 'sidebar')
	{
		$jsonObject = core::config('widget.'.$name_placeholder.'_widget');
		$obj = json_decode($jsonObject, true); // to array

		if($obj)
		{
			if(!empty($obj[$name_placeholder][0]))
			{
				// array of widget path, to include to view
				foreach ($obj[$name_placeholder] as $o => $value) 
				{
					$active_widgets[$value['name'].$o] = View::factory($value['path'], array('class'=>View::factory($value['class'])));
				}
			} else $active_widgets = NULL;

		} else $active_widgets = NULL;
		
		return $active_widgets;
	}

	public static function save_active()
	{

	}

}//end class Widget