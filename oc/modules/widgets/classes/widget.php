<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Abstract class Widget to use in all the other widgets
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */

abstract class Widget{

	public static $fields = array();
	

	/**
	 * $deactivate_placeholder 
	 * limit placeholders for this widget 
	 * (leave empty array for NO restrictions )
	 * 
	 * @var array
	 */
	public static $deactivate_placeholder = array();


	/**
	 * @var  title widget title
	 */
	public static $title;

	/**
	 * @var  description what the widget does
	 */
	public $description;


	public function __construct(){}

	public function set_info(){}

	public static function get_info(){}


	/**
	 * renders the widget view
	 * @return string HTML 
	 */		
	public function render()
	{
		$this->before();

		//get the view file (check if exists in the theme if not default), and inject the data
		//View::factory($widget_name,$data);

		$this->after();

		//return the HTML
	}

	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		// Nothing by default
	}

	/**
	 * Automatically executed after the widget action. Can be used to apply
	 * transformation to the request response, add extra output, and execute
	 * other custom code.
	 *
	 * @return  void
	 */
	public function after()
	{
		// Nothing by default
	}
}