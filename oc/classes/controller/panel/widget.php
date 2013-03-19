<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Widget
 *
 * @author      Slobodan <slobodan.josifovic@gmail.com>
 * @package     Core
 * @copyright   (c) 2012 AdSerum.com
 * @license     GPL v3
 */
class Controller_Panel_Widget extends Auth_Controller {

   public function action_index()
   {
   		$placeholders = Widget::$placeholder; 
        $theme_widgets = Widget::$theme_widgets;
   		
        $widgets = Widget::get_widgets();
        foreach ($widgets as $key => $value) {
            print_r($value);
        }
   		$this->template->content = View::factory('oc-panel/pages/widget', array('placeholder'=>$placeholders,

   																				));
   }

}