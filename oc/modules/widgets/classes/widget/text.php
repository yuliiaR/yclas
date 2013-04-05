<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Text widget
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */


class Widget_Text extends Widget
{

	public function __construct()
	{	

		$this->title = __('Text');
		$this->description = __('HTML textarea');

		$this->fields = array(	
								'text_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Text title displayed'),
						 		  						'default'   => 'Title',
														'required'	=> TRUE),
								
						 		'text_body'  => array(	'type'		=> 'textarea',
						 		  						'display'	=> 'textarea',
						 		  						'label'		=> __('HTML/text content here'),
						 		  						'default'   => 'Text',
														'required'	=> TRUE),

						 		
						 		);
	}




}