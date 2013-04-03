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

	/**
	 * action_index
	 * @return admin widget view 
	 */
   	public function action_index()
   	{

   		//template header
		$this->template->title           	= __('Widgets');
		$this->template->meta_description	= __('Widgets');
				
		// $this->template->styles 			= array('css/jquery.sceditor.min.css' => 'screen');
		// $this->template->scripts['footer'][]= 'js/jquery.sceditor.min.js';
		$this->template->scripts['header'][] = 'js/oc-panel/widget.js';
		$this->template->scripts['header'][] = 'http://code.jquery.com/ui/1.10.2/jquery-ui.js';


   		$placeholders 		= Widgets::$placeholder;
        $theme_widgets 		= Widgets::$theme_widgets;
   		$default_widgets 	= Widgets::$default_widgets;
   		// $basic_information  = Widgets::basic_information();

   		$placeholder_name = $this->request->param('id');
   		// d($placeholder_name);
   		if(in_array($placeholder_name, $placeholders) || empty($placeholder_name))
   		{
	   		foreach ($placeholders as $p => $value) { 
	   			$config = new Model_Config();
	   			$placeholder_ref = $config->where('config_key', '=', $value.'_placeholder')
	   											   ->and_where('group_name', '=', 'widget')
	   											   ->limit(1)->find();

	   	   		if($placeholder_ref->loaded())
	   	   		{
	   	   			$active_widgets[$value] = json_decode(core::config('widget.'.$value.'_placeholder'), true);
	   	   			

                    // get basic info for each widget
                    $basic_info[$value] = NULL;
                    foreach ($active_widgets[$value] as $key => $value) {
                    
                        $widget_class = 'widget_'.preg_replace('/[0-9]/', '', $value);
                        $basic_info[$value] = $widget_class::get_info();
                        
                    } 
	   	   		}
	   	   		else 
	   	   		{
	   	   			unset($placeholders[$p]); // in case placeholder is not found in DB
	   	   			
	   	   		}
	   			
                
	   		}
	   	}
	   	else
	   	{
	   		//throw 404
  			throw new HTTP_Exception_404();	
	   	}
   		

   		$this->template->content = View::factory('oc-panel/pages/widget', array('placeholders'		=>$placeholders, // list of all active placeholders
   																				'default_widgets'	=>$default_widgets, // list of all default widgets
   																				'placeholder_name'	=>$placeholder_name, // single placeholder name
   																				'active_widgets'	=>$active_widgets, // list of all active widgets
   																				'basic_info'        =>$basic_info
   																				));

   		
   	}

   	/**
   	 * action_save
   	 * @return save widget (make active)
   	 */
   	public function action_save()
   	{
   		$save_widget = $this->request->query();
   		
   		foreach ($save_widget as $key => $value) {
   			$placeholder_name = $value;
   			$placeholder_id = $placeholder_name.'_placeholder';

   			$placeholder = core::config('widget.'.$placeholder_id);
   			
   			if(!empty($placeholder))
   			{
   				$str = str_replace($key, "", $placeholder, $count); //count if multiple times used same widget	
   				$jsobj_placeholder = json_decode($placeholder, true); // json object decode
   				
   			}
   			else $count = 0;
   			
   			$jsobj_placeholder[] = $key.$count;
   			$placeholder = json_encode($jsobj_placeholder); // to json
   			
   		}

   		// save widget to DB
   		$activate_widget = new Model_Config();
   		$activate_widget = $activate_widget->where('config_key', '=', $placeholder_id)->limit(1)->find();

   		$activate_widget->config_value = $placeholder;

   		try {
   			$activate_widget->save();
   			$this->request->redirect(Route::url('oc-panel', array('controller'=>'widget', 'action'=>'index', 'id'=>$placeholder_name)));
   		} catch (Exception $e) {
   			//throw 500
  			throw new HTTP_Exception_500();		
   		}
   	}

   	/**
   	 * action_remove
   	 * @return remove widget (deactivate)
   	 */
   	public function action_remove()
   	{
   		$remove_widget = $this->request->query();

   		foreach ($remove_widget as $key => $value) {
   			$placeholder_name = $value;
   			$placeholder_id = $placeholder_name.'_placeholder';

   			$placeholder = core::config('widget.'.$placeholder_id);
   			$jsobj_placeholder = json_decode($placeholder, true);
   			$placeholder = array_diff($jsobj_placeholder, array($key));
   		}

   		$deactivate_widget = new Model_Config();
   		$deactivate_widget = $deactivate_widget->where('config_key', '=', $placeholder_id)->limit(1)->find();

   		$deactivate_widget->config_value = json_encode($placeholder);

   		try {
   			$deactivate_widget->save();
   			$this->request->redirect(Route::url('oc-panel', array('controller'=>'widget', 'action'=>'index', 'id'=>$placeholder_name)));
   		} catch (Exception $e) {
   			//throw 500
  			throw new HTTP_Exception_500();		
   		}
   }

}