<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * RSS widget reader
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */


class Widget_Pages extends Widget
{

	public function __construct()
	{	

		$this->title = __('Display pages');
		$this->description = __('Siplays pages');

		$this->fields = array(	
						 		'page_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Page title displayed'),
						 		  						'default'   => 'Pages',
														'required'	=> TRUE),
						 		);
	}

	
	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		$pages = array();
		$this->page_items = $pages;
	}


}