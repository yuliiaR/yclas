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
   		$placeholders = new Model_Config();
   		$placeholders = $placeholders->where('group_name', '=', 'widget')->find_all(); 
   		
   		// list of placeholders
   		foreach ($placeholders as $place => $value) 
   		{
   			$placeholder_name[] = $value->config_key; 
   		}
   		d($placeholder_name);
   		$this->template->content = View::factory('oc-panel/pages/widget', array('placeholder'=>$placeholder_name,

   																				));
   }

}