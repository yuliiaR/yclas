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
        $this->before('oc-panel/pages/widgets/main');

        //template header
        $this->template->title  = __('Widgets');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Widgets')));

        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/widgets.js';


        $this->template->widgets        = Widgets::get_widgets();
        $this->template->placeholders   = Widgets::get_placeholders();

    }
    

   	/**
   	 * action_save
   	 * @return save widget (make active)
   	 */
   	public function action_save()
   	{
   		$save_widget = $this->request->query();
   		
   		foreach ($save_widget as $key => $value) 
        {
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

   		foreach ($remove_widget as $key => $value) 
        {
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